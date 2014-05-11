<?php

class Tweet extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function follower() {
		return $this->belongsTo('Follower', 'follower_id', 'id');		
	}

}