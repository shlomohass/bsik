<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 1.0.1
// Creation Date: 10/05/2020
// Copyright 2020, shlomo hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->Creation, initial.
*******************************************************************************/

/******************************  headers and ini  *****************************/

header('Content-Type: text/html; charset=UTF-8'); 
ini_set("session.gc_maxlifetime", 86400); // 86400
ini_set("session.cookie_lifetime", 86400);  // 86400
error_reporting(-1); // -1 all, 0 don't
ini_set('display_errors', 'on');
define( 'ERROR_METHOD', 'inline'); // inline | redirect | hide
date_default_timezone_set('Asia/Jerusalem');

/******************************  constants  *****************************/

define("BUILD_ON",      "PHP 7");
define("APP_VERSION",   "1.0.1");

/************************** System Configuration & Trace ******************************/
define( 'PLAT_HASH_SALT', '????' );


if (!defined('EXPOSE_OP_TRACE')) define('EXPOSE_OP_TRACE', false); //Don't touch

$conf = [];
/******************************  Configuration - DataBase  *****************************/
//Insert your db credentials:
$conf["db"] = [
    'host'   => '????',  // Ip Address
    'port'   => '3306',  // Port of DB
    'dbname' => '????',  //DB name
    'dbuser' => '????',  //DB user
    'dbpass' => '????'   //DB password
];
//Control what to log & send:
define("SEND_DB_ERRORS", false);
define("SEND_ERRORS_TO", "example@gmail.com");
define("LOG_DB_ERRORS", true);
define("LOG_DB_TO_TABLE", "db_error_log");

/******************************  Configuration - path  *****************************/
//$conf["path"] = [ "domain" => "siktec.net"];
//$conf["path"]["site_base_path"] = "/dev/Examer/";
$conf["path"] = [ "domain" => "http://mydomain.com"]; 
$conf["path"]["site_base_path"]     = DS."Server".DS."bsik".DS;
$conf["path"]["site_admin_path"]    = $conf["path"]["site_base_path"].'admin'.DS;
$conf["path"]["site_base_url"]      = $conf["path"]["domain"];
$conf["path"]["dynamic_pages"]      = $conf["path"]["site_base_path"]."struct".DS."pages".DS."dynamic".DS;
$conf["path"]["required_pages"]     = $conf["path"]["site_base_path"]."struct".DS."pages".DS."required".DS;

/******************************  PATH CONSTANTS  *****************************/
define('URL_DOMAIN',            $conf["path"]["domain"]);
define('PATH_BASE',             $conf["path"]["site_base_path"]);
define('PATH_IMG',              PATH_BASE."img".DS);
define('PATH_LIB',              PATH_BASE."lib".DS);
define('PATH_CSS',              PATH_LIB."css".DS);
define('PATH_JS',               PATH_LIB."js".DS);
define('PATH_IMPORT',           PATH_LIB."import".DS);
define('PATH_STRUCT',           PATH_BASE."struct".DS);
define('PATH_CORE',             PATH_STRUCT."core".DS);
define('PATH_BLOCKS',           PATH_STRUCT."blocks".DS);
define('PATH_PAGES',            PATH_STRUCT."pages".DS);
define('PATH_DYANMIC_PAGES',    $conf["path"]["dynamic_pages"] );
define('PATH_REQUIRED_PAGES',   $conf["path"]["required_pages"]);

/******************************  Page defaults  *****************************/
$conf["page"] = array();
$conf["page"]["meta"] = [
    "lang"          => "en",
    "direction"     => "rtl",
    "charset"       => "utf-8",
    "viewport"      => "width=device-width, initial-scale=1",
    "author"        => "Shlomo Hassid",
    "description"   => "",
    "title"         => "SIK FrameWork",
    "icon"          => "https://milligram.github.io/images/icon.png"
];

/******************************  FW signup  *****************************/
$conf["g-sign"] = array(
    "app-name"      => "SIK FrameWork",
    "client-id"     => "????", // G client ID
    "client-secret" => "????", // G client secret
    "redirect"      => $conf["path"]["domain"].$conf["path"]["site_base_path"]."?page=gredirect"
);

/******************************  ERROR CODES  *****************************/