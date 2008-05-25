<?php

class controller {

	public $parameters = array();

	function __construct() {
		//Why not add authorisation checks in here, then all controllers can inherit
	}
	
	function index() {
		echo "No index method defined for this controller";
	}

}