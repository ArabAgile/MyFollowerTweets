<?php

class Follower extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];


	public function tweets() {
		return $this->hasMany('Tweet', 'follower_id', 'id');
	}

}