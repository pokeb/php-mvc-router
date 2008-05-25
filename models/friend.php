<?php

/*
This is an example object that takes two parameters in the constructor
This might be typical if this class maps a database table with a composite key
*/

class friend extends model {

	public $user = "";
	public $friend = "";

	function __construct($user="",$other_user="") {
		//Normally you'd connect to the database to fetch the object's properties here
		if ($user != "" && $other_user != "") {
			$this->user = $user;
			$this->friend = $other_user;
			$this->is_valid = true;
		}
	}

}