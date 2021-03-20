<?php
/******************************************************************************/
// Created by: SIKTEC.
// Release Version : 1.0.0
// Creation Date: 2021-03-17
// Copyright 2021, SIKTEC.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.0:
    ->initial
*******************************************************************************/


/****************************************************************************/
/****************************  INCLUDES  ************************************/
/****************************************************************************/
require_once PLAT_PATH_CORE.DS.'BsikApi.class.php';

/****************************************************************************/
/****************************  INITIATE  ************************************/
/****************************************************************************/
//This will make sure that errors are logged but not displayed
ini_set("display_errors", "on");

//Initialize Api instance:
if (!isset($AApi)) {
    $AApi = new BsikApi(
        Base::get_session("csrftoken"),     //CSRF TOKEN
        PLAT_ADMIN_PANEL_API_DEBUG_MODE,    //Operation Mode
        "bsikapi-manage"                   //logger chanel
    );
}

//This is Admin endpoint so force Login:
if (!isset($Admin) || !$Admin->is_signed) {
    $AApi->update_answer_status(403, "You must be registered and signed as an Admin.");
    $AApi->answer(true);
    exit();
}

//Set a user identifier for logging:
$AApi->set_user_string($Admin->admin_identifier());

/****************************************************************************/
/**********************  CORE ADMIN API METHODS  ****************************/
/****************************************************************************/

/******************************  Get from tabels  *****************************/
$AApi->register_endpoint(new BsikApiEndPoint(
    $name = "get_from_table", 
    $params = [ // Defines the expected params with there defaults.
        "table_name" => null, //null indicates no default.
        "fields"     => ["id", " email "],
        "limit"      => '5'
    ],
    $filters = [ // Defines filters to apply -> this will modify the params.
        "table_name" => BsikValidate::add_procedure("trim")::add_procedure("strchars","A-Z","a-z","0-9","_")::create_filter(),
        "fields"     => BsikValidate::add_procedure("trim")::create_filter(),
        "limit"      => BsikValidate::add_procedure("type", "number")::create_filter()
    ],
    $validation = [ // Defines Validation rules of this endpoint.
        "table_name" => BsikValidate::add_cond("required")::add_cond("type","string")::create_rule(),
        "fields"     => BsikValidate::add_cond("type","array")::create_rule(),
        "limit"      => BsikValidate::add_cond("type","integer")::create_rule()
    ],
    //The method to execute -> has Access to BsikApi
    function(BsikApi $Api, array $args) {
        $data = [];
        try {
            $data = $Api::$db->get($args["table_name"], $args["limit"], $args["fields"]);
            $Api->update_answer_status(200);
        } catch (Exception $e) {
            $data = [];
            $Api->update_answer_status(500);
        }
        $Api->request->answer->data = $data;
        return true;
    }
));


/****************************************************************************/
/**********************  LOAD MODULE DEFINED API  ***************************/
/****************************************************************************/

$AApi->register_endpoint(new BsikApiEndPoint(
    $name = "get_for_datatable", 
    $params = [ // Defines the expected params with there defaults.
        "table_name" => null,
        "order"     => null, //null indicates no default.
        "search"    => "",
        "sort"      => null,
        "fields"    => ["*"]
    ],
    $filters = [ // Defines filters to apply -> this will modify the params.
        "table_name" => BsikValidate::add_procedure("trim")::add_procedure("strchars","A-Z","a-z","0-9","_")::create_filter(),
        "search"    => BsikValidate::add_procedure("trim")::add_procedure("strchars","A-Z","a-z","0-9","_")::create_filter(),
        "fields"    => BsikValidate::add_procedure("trim")::add_procedure("strchars","A-Z","a-z","0-9","_")::create_filter()
    ],
    $validation = [ // Defines Validation rules of this endpoint.
        "search" => BsikValidate::add_cond("required")::add_cond("type","string")::create_rule(),
        "search" => BsikValidate::add_cond("type","string")::create_rule(),
        "fields" => BsikValidate::add_cond("type","array")::create_rule()
    ],
    //The method to execute -> has Access to BsikApi
    function(BsikApi $Api, array $args) {
        $data = [];
        $table  = $args["table_name"];
        $search = $args["search"];
        $fields = $args["fields"];
        //Define search:
        $search = !empty($search) ? ["term" => $search, "in-columns" => $fields] : [];
        if (!empty($search)) {
            $Api::$db->where("(");
            foreach($search["in-columns"] as $i => $col) {
                if ($i === 0) $Api::$db->where($col, "%".$search["term"]."%", "like");
                else $Api::$db->orWhere($col, "%".$search["term"]."%", "like");
            }
            $Api::$db->where(")");
        }
        try {
            $data = $Api::$db->get($table, null, $fields);
            $Api->update_answer_status(200);
        } catch (Exception $e) {
            $data = [];
            $Api->update_answer_status(500);
        }
        $Api->request->answer->data = $data;
        return true;
    }
));




/****************************************************************************/
/*************************  EXECUTE API REQUEST  ****************************/
/****************************************************************************/

$_REQUEST["request_type"]  = "get_for_datatable";
$_REQUEST["request_token"] = Base::get_session("csrftoken");
$_REQUEST["limit"]         = 1;
$_REQUEST["table_name"]    = " @#$%users@#$@$ ";
$AApi->parse_request($_REQUEST);
$AApi->execute();
$AApi->answer(true);

//$AApi->logger->info("Testing logger", ["value" => "SIKTEC"]);


// $rule = "required->type::integer";
// $message = [];
// $valid = false;
// try {
//     $valid = BsikValidate::validate(12, $rule, $message);
// } catch (Throwable $t) {
//     print($t->getMessage());
// }
// var_dump($valid);
// var_dump($message);

// $rule = BsikValidate::add_cond("minlen", 4)
//                     ::add_cond("length", 2, 5)
//                     ::add_cond("maxlen")
//                     ::create_rule();
// $parsed = BsikValidate::parse_rule($rule);
// var_dump($rule);
// var_dump($parsed);
