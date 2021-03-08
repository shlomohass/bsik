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
define("ROOT_PATH", dirname(__FILE__) );

/******************************  Requires       *****************************/

require_once ROOT_PATH.DS."..".DS."struct".DS.'conf.php';
require_once PLAT_PATH_VENDOR."..".DS.'autoload.php';
require_once PLAT_PATH_CORE."..".DS.'Base.class.php';
require_once PLAT_PATH_CORE."..".DS.'Admin.class.php';
require_once PLAT_PATH_CORE."..".DS.'Page.class.php';

Trace::add_step(__FILE__, "Controller - manage index");

if(!session_id()){ session_start(); }

/*********************  Load Conf and DataBase  *****************************/
Base::configure($conf);
Trace::add_trace("Loaded Base Configuration Object",__FILE__, $conf);
Base::connect_db();
Trace::add_trace("Establish db connection",__FILE__);

/******************************  Load User      *****************************/
$User = new User();
Trace::add_trace("Loaded Admin Object", __FILE__);
Trace::reg_vars(["user roles" => $User->roles]);

/******************************  Load Modules *****************************/


/******************************  Load Pages *****************************/

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
Trace::add_trace("Loaded Google client", __FILE__);

/******************************  User login / logout   *****************************/
//Check user signed or not:
$User->initial_user_login_status($gClient, $google_oauth);
Trace::reg_vars(["User signed" => $User->is_signed]);
Trace::add_trace("User login status",__FILE__, $User->user_data);

/******************************  Build Page      *****************************/
Trace::add_step(__FILE__,"Loading and building page:");
switch ($Page->request["type"]) {
    case "page": {
        if ($Page->request["page"] === 'gredirect') {
            
            require PLAT_PATH_PAGES.DS."gredirect.php";

        } elseif ($Page->load_page()) {

            Trace::add_trace("Loaded requested page ",__FILE__, $Page->definition);
            Trace::add_trace("Parsed page settings ",__FILE__, $Page->settings);
            Trace::reg_vars(["Page Settings" => $Page->settings]);
            require PLAT_PATH_PAGES.DS."render.php";

        } else {
            Trace::add_trace("Failed load requested page", __FILE__, $Page->definition);
        }
    } break;
    case "api": {

    } break;
    default: {
        // Not found page:
        $Page::error_page("page_request_notfound");
    }
}

//Render Platform Trace:
    Trace::expose_trace();