<?php
$GLOBALS['routes'] = array(

	//Requests to /hello-world will go to the hello_world controller's 'index' action
	'/hello-world' => 'hello_world',		
	
	//Requests to /hello-world will go to the hello_world controller's 'say-hello' action
	'/hello-world/say-hi' => 'hello_world:say_hello', 
	
	//Requests to /hello-world/something will go to hello_world controller's 'something' action
	'/hello-world/[action]' => 'hello_world',

	//Requests to /projects will go to the projects controller inside the admin folder in controllers
	'/admin/projects/[action]' => 'admin/projects',	
	
	//Requests to /non-existent-controller will include /views/non_existent_controller.php, as the controller itself doesn't exist
	'/non-existent-controller' => 'non_existent_controller',	
	
	//Requests to /projects will go to the projects controller's 'index' action
	'/projects' => 'projects',
	
	//Requests to /projects/1234 will instantiate a project instance (using 1234 as the parameter for the project object's constructor), and pass it as a parameter to the 'view' action
	'/projects/(project)' => 'projects:view',
	
	//Requests to /projects/1234/delete will instantiate a project instance, and pass it as a parameter to the 'delete' action
	'/projects/(project)/delete' => 'projects:delete',
	
	//Requests to /projects/1234/items/567 will instantiate a project instance and an item instance, and pass them as parameters to the 'view_item' action
	'/projects/(project)/items/(item)'	=> 'projects:view_item',	
	
	//Requests to /friends/bobjones/johnsmith will go to the friends controller. Its '$parameters' instance varable will be an associative array like this: { "user" => "bobjones", "friend" => "johnsmith" }
	//Using approach is useful if you pass parameters in your url that don't map on to model keys, or if a model uses a composite key - see the friends controller for more
	'/friends/:user/:friend' => 'friends:view_friend'


);