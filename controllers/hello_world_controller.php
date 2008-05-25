<?php

/*
An example of a very simple controller that doesn't use views
*/

class hello_world_controller extends controller {

	// GET /hello-world
	function index() {
		echo "This is the index for the hello_world_controller";
		exit;
	}
	
	// GET /hello-world/say-hello
	function say_hello() {
		echo "This is hello_world_controller saying hello!";
		exit;
	}
	
	// GET /hello-world/another-action
	function another_action() {
		echo "This is the 'another_action' method for the hello_world_controller";
		exit;
	}

}