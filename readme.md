## Tweets of my followers

### Requirements

	PHP >= 5.4.0
	MCrypt PHP Extension

### Instructions

- clone repository
		git clone https://github.com/ArabAgile/MyFollowerTweets.git test

- Go inside dir and run: 
		composer update

- Change files permissions for storage dir by running: 
		chmod -R 0777 app/storage

- Configurations you can edit: 
		/app/config/app.php

		// Cache lifetime in minutes
		'cache_lifetime' => 10,

		// Twitter screen name
		'twitter_screen_name' => 'ammaralrfai’, // My twitter screen name

		'twitter_follower_per_page' => 100,  // Followers per page/ change to less than 100 to see pager
		'twitter_follower_per_request' => 100,
		'twitter_tweets_per_request' => 1,

- Use correct twitter API auth & access token details by changing configuration file under:
/app/config/packages/thujohn/twitter/config.php

- SQLite should be installed and configured properly with PHP as PDO

- Memcached should be installed and configured properly with PHP as memcached extension (not memcache), if the configurations is not the default (host,port,weight) you can edit the config details in: app/config/cache.php 

- Navigate to /public/ folder in browser and see what your followers tweeting...


### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

