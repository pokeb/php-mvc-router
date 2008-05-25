<?php
require_once('helpers/utility.php');
require_once('helpers/router.php');
require_once('config/config.php');
require_once('controllers/controller.php');
require_once('config/routes.php');



mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');

session_start();
