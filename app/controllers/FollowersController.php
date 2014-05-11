<?php

class FollowersController extends \BaseController {


	/**
	 * Setup DB migrations
	 */
	public function __construct() {
		Artisan::call('migrate');
	}

	/**
	 * List my tweets
	 * @return Response
	 */
	function showFeed() {
		
		// Save followers & tweets if not already saved
		$this->_saveFollowers();
		$this->_saveTweets();

		$followers = Follower::with('tweets')->paginate(Config::get('app.twitter_follower_per_page'));
		// return Response::json($followers);
		return View::make('feed', ['followers' => $followers]);
	}

	/**
	 * Store requested followers in storage.
	 *
	 * @return Response
	 */
	public function _saveFollowers($cursor = -1)
	{
		$followers = $this->_getFollowers($cursor);

		if (is_array($followers) && isset($followers['errors']))
			return 'Error in twitter API. Code ' . $followers['errors'][0]['code'] . ' - '. $followers['errors'][0]['message'];

		// No response or no followers! escape.
		if (!is_array($followers) || !isset($followers['users']))
			return;

		// Save all followers in this cursor
		if (count($followers['users']) > 0) {

			foreach ($followers['users'] as $user) {

				// Check if alredy exists follower
				$follower = Follower::where('twitter_id', '=', $user['id'])->first();
				if (is_null($follower)) {
					// Create new follower
					$follower = new Follower();
					$follower->twitter_id = $user['id'];
					$follower->name = $user['name'];
					$follower->photo = $user['profile_image_url'];
					$follower->screen_name = $user['screen_name'];
					$follower->save();

				} else {
					// Update follower info
					$follower->twitter_id = $user['id'];
					$follower->name = $user['name'];
					$follower->photo = $user['profile_image_url'];
					$follower->screen_name = $user['screen_name'];
					$follower->update();
				}
			}
		}


		// You've reached last cursor - you got them all :)
		if ($followers['next_cursor'] == 0)
			return;

		// OR...

		// Request the next cursor if any
		$this->_saveFollowers($followers['next_cursor']);
	}

	/**
	 * Store follower tweets in storage.
	 *
	 * @return Response
	 */
	public function _saveTweets()
	{
		$followers = Follower::all();
		foreach ($followers as $follower) {
			$tweets = $this->_getTweets($follower->screen_name);

			if (is_array($tweets) && isset($tweets['errors']))
				return 'Error in twitter API. Code ' . $tweets['errors'][0]['code'] . ' - '. $tweets['errors'][0]['message'];

			// No response or no tweets! escape.
			if (!is_array($tweets))
				return;

			$_tweet = $tweets[0];

			// Check if alredy exists tweet
			$tweet = Tweet::where('twitter_id', '=', $_tweet['id'])->first();
			if (is_null($tweet)) {
				// Create new tweet
				$tweet = new Tweet();
				$tweet->tweet_id = $_tweet['id'];
				$tweet->follower_id = $follower->id;
				$tweet->tweet = $_tweet['text'];
				$tweet->save();
			} else {
				// Update tweet info
				$tweet->tweet_id = $_tweet['id'];
				$tweet->follower_id = $follower->id;
				$tweet->tweet = $_tweet['text'];
				$tweet->update();
			}
			
		}

	}

	/**
	 * Get followers as per specified cursor
	 *
	 * @return Response
	 */
	public function _getFollowers($cursor = -1)
	{
		$suff = $cursor == -1 ? '1' : $cursor;

		return Cache::remember('followers_' . $suff, Config::get('app.cache_lifetime'), function() use ($cursor)
		{
		    return Twitter::getFollowers(
				array('screen_name' => Config::get('app.twitter_screen_name'), 
					  'skip_status' => true, 'include_user_entities' => false, 
					  'format' => 'array', 'count' => Config::get('app.twitter_follower_per_request'),
					  'cursor' => $cursor));
		});
	}

	/**
	 * Get user tweets
	 *
	 * @return Response
	 */
	public function _getTweets($user_screen_name)
	{
		return Cache::remember('tweets_' . $user_screen_name, Config::get('app.cache_lifetime'), function() use ($user_screen_name)
		{
			return Twitter::getUserTimeline(
				array('screen_name' => $user_screen_name, 
					  'trim_user' => true, 'include_rts' => true, 
					  'format' => 'array', 'count' => Config::get('app.twitter_tweets_per_request'))
				);
		});
	}

}