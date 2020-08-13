<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 1.0.1
// Creation Date: 10/05/2020
// Copyright 2020, shlomo hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->Creation - Initial
*******************************************************************************/

define('DS', DIRECTORY_SEPARATOR);

/******************************  Requires       *****************************/

require_once 'struct'.DS.'conf.php';
require_once 'vendor'.DS.'autoload.php';
require_once PATH_CORE.'Excep.class.php';
require_once PATH_CORE.'Trace.class.php';
require_once PATH_CORE.'Db.class.php';
require_once PATH_CORE.'Base.class.php';
require_once PATH_CORE.'User.class.php';
require_once PATH_CORE.'Page.class.php';

Trace::add_step(__FILE__,"Loading Code - Require");
Trace::add_trace("Configuration Object",__FILE__, $conf);

if(!session_id()){ session_start(); }
/******************************  Load DataBase      *****************************/
$Data = new DB($conf['db']);
/******************************  Load User      *****************************/
$User = new User($conf, $Data);

/******************************  Load Page      *****************************/
Trace::add_step(__FILE__,"Loading Page structure");
$Page = new Page($conf);
Trace::add_trace("Page class loaded - basic page arguments", __FILE__, $Page->request, $Page->token, $Page->dynamic_pages);
Trace::reg_vars(["Request" => $Page->request, "Token" => $Page->token, "Pages" => $Page->dynamic_pages]);

/******************************  G Client  *****************************/

$gClient = new Google_Client();
$gClient->setApplicationName($conf["g-sign"]["app-name"]);
$gClient->setClientId($conf["g-sign"]["client-id"]);
$gClient->setClientSecret($conf["g-sign"]["client-secret"]);
$gClient->setRedirectUri($Page->parse_slash_url_with($conf["g-sign"]["redirect"]));
$gClient->setScopes([
    Google_Service_Plus::PLUS_LOGIN,
    Google_Service_PeopleService::USERINFO_EMAIL,
    Google_Service_PeopleService::USER_BIRTHDAY_READ
]);
$User->gSignUrl = $gClient->createAuthUrl();
$google_oauth = new Google_Service_Oauth2($gClient);

//$datee = new Google_Service_PeopleService_Date();
//$datee->
/******************************  User login / logout   *****************************/
//Check user signed or not:
$User->initial_user_login_status($gClient, $google_oauth);
Trace::add_trace("User Object",__FILE__, $User);
/******************************  Build Page      *****************************/

Trace::add_step(__FILE__,"Loading and building page:");
switch ($Page->request["page"]) {
    case "main":
        include PATH_REQUIRED_PAGES."main.php";
        break;
    case "app":
        include PATH_REQUIRED_PAGES."app.php";
        break;
    case "manage":
        include PATH_REQUIRED_PAGES."manage.php";
        break;
    case "gredirect":
        include PATH_REQUIRED_PAGES."gredirect.php";
        break;
    case "error":
        include PATH_REQUIRED_PAGES."error.php";
        break;
    default:
        if (in_array($Page->request["page"].".php", $Page->dynamic_pages)) {
            // Dynamic page:
            include PATH_DYANMIC_PAGES.$Page->request["page"].".php";
        } else {
            // Not found page:
            $Page->error_page("page_request_notfound");
        }
}

/******************************  Expose Trace    *****************************/
Trace::expose_trace();