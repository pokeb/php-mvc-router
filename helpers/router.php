<?php

/*
Ben's Magic PHP routing class
The static (class) methods in this class are used to find an appropriate controller/action to handle our request
*/

class router {

	static function route() {
	
		$url = explode('?',$_SERVER['REQUEST_URI']);
		$path = mb_strtolower($url[0]);
		while (substr($path, -1) == '/') {
			$path = mb_substr($path,0,(mb_strlen($path)-1));
		}
		$path_components = explode('/', $path);


		//default actions are called 'index'
		$action = "index";
		
		//Handle home page requests
		if (count($path_components) == 1) {
			router::perform_controller_action("home",$action,array(),array());
		}
	
		
		//Loop through all the routes we defined in route.php, and try to find one that matches our request
		foreach ($GLOBALS['routes'] as $route => $controller) {
			$route_components = explode("/",$route);
			$action = "index";
			$i=0;
			$objects = array();
			$goodRoute = true;
			$path_components = array_pad($path_components, count($route_components), '');
			$parameters = array();
	
			//Handle routes that call a specific action
			$controller_action_array = explode(":",$controller);
			$controller = $controller_action_array[0];
			if (count($controller_action_array) == 2) {
				$action = $controller_action_array[1];
			}
	
			//Loop through each component of this route until we find a part that doesn't match, or we run out of url
			foreach ($route_components as $route_component) {
				
		
				//This part of the route is a named parameter
				if (substr($route_component,0,1) == ":") {
					$parameters[substr($route_component,1)] = $path_components[$i];
		
		
				//This part of the route is an action for a controller
				} elseif ($route_component == "[action]") {
					if ($path_components[$i] != "") {
						$action = str_replace("-","_",$path_components[$i]);
					}
					
				//This part of the route will require that we create an object
				} elseif (substr($route_component,0,1) == "(" && substr($route_component,-1,1) == ")") {
					$reflection_obj = new ReflectionClass(substr($route_component,1,strlen($route_component)-2)); 
					$object = $reflection_obj->newInstanceArgs(array($path_components[$i]));
					

					$objects[] = $object;
					

				//Part of the url that isn't an action or an object didn't match, this definitely isn't the right route
				} elseif ($route_component != $path_components[$i] && str_replace("-","_",$route_component) != $path_components[$i]) {
					//echo "Bad match: ".str_replace("-","_",$route_component)." != ".$path_components[$i]."<br />";
					$goodRoute = false;
					break;
				}
				$i++;
			}
			
			//This route is a match for our request, let's get the controller working on it
			if ($goodRoute && ($i >= count($path_components) || $path_components[$i] == "")) {

				router::perform_controller_action($controller,$action,$objects,$parameters);
			}
		}
		
		
		error_404();
	}
	
	//Look for a controller file matching the request, and failing that, a view
	static function perform_controller_action($class_path,$action,$objects,$parameters) {
		
		//We treat 'new' the same as 'edit', since they generally contain a lot of the same code
		if ($action == "new") {
			$action = "edit";
		}
		
		//Let's look for a controller
		$controller_path = SITE_PATH."/controllers/".$class_path."_controller.php";


		if (file_exists($controller_path)) {
			require_once($controller_path);
			
			$class_path_components = explode("/",$class_path);
			$class = $class_path_components[count($class_path_components)-1];
			$controller_class = $class."_controller";
			if (!method_exists($controller_class,$action)) {
				if (router::render_view($class_path,$action)) {
					exit;
				} else {
					fatal_error("$controller_class does not respond to $action");
				}
			}
			
			$controller = new $controller_class();
			$controller->parameters = $parameters;
			call_user_func_array(array($controller,$action),$objects);
			exit;
		}
		
		//If no controller was found, we'll look for a view
		if (router::render_view($class_path,$action)) {
			exit;
		}
	}
	
	static function render_view($class_path,$action) {
		$view_path = SITE_PATH."/views/$class_path/".$action.".php";
		if (file_exists($view_path)) {
			$controller = new controller();
			require_once($view_path);
			return true;
		}
		return false;
	}
}

