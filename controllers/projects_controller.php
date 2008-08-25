<?php

/*
An example of a simple controller using views
*/

class projects_controller extends controller {

	// GET /projects
	function index() {
		require_once("views/projects/index.php");
		exit;
	}
	
	// GET /projects/1234
	function view($project) {
		require_once("views/projects/project.php");
		exit;
	}
	
	// GET /projects/1234/delete
	function delete($project) {
		//This is just an example, so it doesn't actually delete anything
		$project->delete();
		header("Location: /projects");
		exit;
	}

	// GET //projects/1234/items/567
	function view_item($project,$item) {
		require_once("views/projects/item.php");
		exit;
	}

}