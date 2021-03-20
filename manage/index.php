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
define("ROOT_PATH", dirname(__FILE__).DS.".." );

/******************************  Requires       *****************************/

require_once ROOT_PATH.DS."struct".DS.'conf.php';
require_once PLAT_PATH_VENDOR.DS.'autoload.php';
require_once PLAT_PATH_CORE.DS.'Base.class.php';
require_once PLAT_PATH_CORE.DS.'Admin.class.php';
require_once PLAT_PATH_MANAGE.DS.'core'.DS.'AdminPage.class.php';

Trace::add_step(__FILE__, "Controller - manage index");

if(!session_id()){ session_start(); }

/*********************  Load Conf and DataBase  *****************************/
Base::configure($conf);
Trace::add_trace("Loaded Base Configuration Object",__FILE__, $conf);
Base::connect_db();
Trace::add_trace("Establish db connection",__FILE__);

/******************************  Load Admin      *****************************/
$Admin = new Admin();
Trace::add_trace("Loaded Admin Object", __FILE__);
Trace::reg_vars(["admin levels" => $Admin->levels]);

/******************************  User login / logout   *****************************/
//Check user signed or not:
$Admin->admin_login();
$Admin->initial_admin_login_status();
Trace::reg_vars(["Admin signed" => $Admin->is_signed]);
Trace::add_trace("Admin login status",__FILE__, $Admin->admin_data);


/******************************  Load Modules And Pages *****************************/
$APage = new APage(
    $Admin->admin_identifier(), // For logging Admin identifier
    "bsikrender-manage",        // For the chanel to use  
);
Trace::add_trace("Loaded AdminPage object", __FILE__, ["request" => $APage->request, "token" => $APage->token]);
Trace::reg_vars(["Requested module" => $APage->request]);
Trace::reg_vars(["Available modules" => $APage->modules]);

/******************************  Build Page      *****************************/
Trace::add_step(__FILE__,"Loading and building page:");
switch ($APage->request["type"]) {
    case "module": {
        //Must be signed in:
        if (!$Admin->is_signed) {
            require_once PLAT_PATH_MANAGE.DS."pages".DS."login.php";
        }
        //Make sure Module Exists:
        elseif (!$APage->isset_module()) {
            $APage::error_page("module_not_set");
        }
        //Make sure exists and is allowed?
        elseif (!$APage->is_allowed_to_use($Admin)) {
            $APage::error_page("admin_is_not_allowed");
        } else {
            //Load the platform that will also render the module:
            $APage->load_module();
            require_once PLAT_PATH_MANAGE.DS."pages".DS."base.php";
        }
        Trace::expose_trace();
    } break;
    case "api": {
        //Must be signed in:
        //Load core Api end points
        require_once PLAT_PATH_MANAGE.DS."core".DS."AdminApi.php";
        //Load module extended methods:
    } break;
    case "error": {
        echo "error";
        var_dump($_REQUEST);
    } break;
    case "logout": {
        if ($Admin->is_signed) {
            $Admin->admin_logout();
        }
        $APage::jump_to_page();
    }
    break;
    default: {
        // Not found page:
        $APage::error_page("module_type_not_set");
        Trace::expose_trace();
    }
}

