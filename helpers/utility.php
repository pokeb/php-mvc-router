<?php

function error_404() {
	header("HTTP/1.0 404 Not Found");
	include_once("views/404.php");
	exit;
}

function fatal_error($error) {
	die("Something went wrong: $error");
}


//automatically load classes from the models folder
//see -> http://us3.php.net/manual/en/language.oop5.autoload.php
function __autoload($class) {
	
	if (file_exists(SITE_PATH."/models/$class.php")) {
		require_once(SITE_PATH."/models/$class.php");
		return;
	}

	fatal_error("Cannot find class '$class'");
}