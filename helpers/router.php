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
		$pathComponents = explode('/', $path);


		//default actions are called 'index'
		$action = "index";
		
		//Handle home page requests
		if (count($pathComponents) == 1) {
			router::performControllerAction("home",$action,array(),array());
		}
		
		
		//Loop through all the routes we defined in route.php, and try to find one that matches our request
		foreach ($GLOBALS['routes'] as $route => $controller) {
			$routeComponents = explode("/",$route);
			$action = "index";
			$i=0;
			$objects = array();
			$goodRoute = true;
			$pathComponents = array_pad($pathComponents, count($routeComponents), '');
			$parameters = array();
	
			//Handle routes that call a specific action
			$controllerActionArray = explode(":",$controller);
			$controller = $controllerActionArray[0];
			if (count($controllerActionArray) == 2) {
				$action = $controllerActionArray[1];
			}
	
			//Loop through each component of this route until we find a part that doesn't match, or we run out of url
			foreach ($routeComponents as $routeComponent) {
				
		
				//This part of the route is a named parameter
				if (substr($routeComponent,0,1) == ":") {
					$parameters[substr($routeComponent,1)] = $pathComponents[$i];
		
		
				//This part of the route is an action for a controller
				} elseif ($routeComponent == "[action]") {
					if ($pathComponents[$i] != "") {
						$action = $pathComponents[$i];
					}
					
				//This part of the route will require that we create an object
				} elseif (substr($routeComponent,0,1) == "(" && substr($routeComponent,-1,1) == ")") {
					$reflectionObj = new ReflectionClass(substr($routeComponent,1,strlen($routeComponent)-2)); 
					$object = $reflectionObj->newInstanceArgs(array($pathComponents[$i]));
					
					//If we couldn't make an object and this part of the url isn't empty, we probably wanted a different route
					if (!$object->is_valid && $pathComponents[$i] != "") {
						$goodRoute = false;
					}
					$objects[] = $object;
					

				//Part of the url that isn't an action or an object didn't match, this definitely isn't the right route
				} elseif ($routeComponent != $pathComponents[$i]) {
					#echo "Bad match $routeComponent != ".$pathComponents[$i]."<br />";
					$goodRoute = false;
					break;
				}
				$i++;
			}
			
			//This route is a match for our request, let's get the controller working on it
			if ($goodRoute && ($i >= count($pathComponents) || $pathComponents[$i] == "")) {
				router::performControllerAction($controller,$action,$objects,$parameters);
			}
		}
		
		
		error_404();
	}
	
	//Look for a controller file matching the request, and failing that, a view
	static function performControllerAction($path,$action,$objects,$parameters) {
		
		//Nice urls: treat /hello-world the same as /hello_world
		$action = str_replace("-","_",$action);
		
	
		//First, let's look for a controller
		$controllerPath = SITE_PATH."/controllers/".$path."_controller.php";


		if (file_exists($controllerPath)) {
			require_once($controllerPath);
			if ($action == "new") {
				$action = "edit";
			}
			$class = explode("/",$path);
			$class = $class[count($class)-1]."_controller";
			if (!method_exists($class,$action)) {
				fatal_error("$class does not respond to $action");
			}
			
			$controller = new $class();
			$controller->parameters = $parameters;
			call_user_func_array(array($controller,$action),$objects);
			exit;
		}
		
		//If no controller was found, we'll look for a view
		$viewPath = SITE_PATH."/views/".$path.".php";
		if (file_exists($viewPath)) {
			require_once($viewPath);
			exit;
		}
	}

}
