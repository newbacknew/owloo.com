<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
## We need these globals in the system for file inclutions and uploads etc. ##
define('SYSTEM_PATH', dirname( __FILE__ ));
define('BASE_PATH', str_replace(array('/system','\system'),'',dirname( __FILE__ )));
define('IN_SYSTEM', true);

if(!isset($_owloo_config_defined) || !$_owloo_config_defined)
    require_once(str_replace(array('/userMgmt/system','\userMgmt\system'),'',dirname( __FILE__ )).'/owloo_config.php');

$isExplorerLegacy = false;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8')  || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9'))
	$isExplorerLegacy = true;

## Lets create our own REQUEST Global which is just a tad more secure than the normal REQUEST ##
$_REQUEST = array_merge($_POST, $_GET);

## Load the error handler before anything else ##
require(SYSTEM_PATH.'/classes/class_errorhandler.php');
$errHandler = new ErrorHandler();
set_error_handler(array($errHandler, 'logError'),E_ALL);

## Get all the classes used by the user system ##
require(SYSTEM_PATH.'/classes/class_sessions.php');
require(SYSTEM_PATH.'/classes/class_caching.php');
require(SYSTEM_PATH.'/classes/class_administration.php');
require(SYSTEM_PATH.'/classes/class_usersystem.php');
require(SYSTEM_PATH.'/classes/class_memberships.php');
require(SYSTEM_PATH.'/classes/class_forum.php');
require(SYSTEM_PATH.'/classes/class_profiles.php');
require(SYSTEM_PATH.'/classes/class_friendsystem.php');
require(SYSTEM_PATH.'/classes/class_privatemsg.php');
require(SYSTEM_PATH.'/classes/class_invites.php');
require(SYSTEM_PATH.'/classes/class_search.php');
require(SYSTEM_PATH.'/classes/class_useruploads.php');
require(SYSTEM_PATH.'/classes/class_languages.php');
require(SYSTEM_PATH.'/templates/class_template.php');
require(SYSTEM_PATH.'/classes/class_triggers.php');
require(SYSTEM_PATH.'/classes/class_ipn.php');
require(SYSTEM_PATH.'/classes/class_security.php');

## Instanciate the user system ##
## You can now access the whole system via: $site->function_name();
$site = new Security($errHandler);

// Process logout request if available
$site->process_logout();

//check if there are any triggers, if so, we need to redirect the user
$site->checkTriggers();
					
// Check if the system is installed, else redirect the user to the install.php file.
if(!$site->installed && $site->curpage != 'install'){
	header("Location: install.php");
	exit;
}