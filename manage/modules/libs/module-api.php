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
//Initialize Api instance:
if (!isset($AApi)) $AApi = new BsikApi(Base::get_session("csrftoken")); //For intellisense 

/****************************************************************************/
/**********************  MODULE API END POINTS  ****************************/
/****************************************************************************/

/******************************  Get from tabels  ***************************/
$AApi->register_endpoint(new BsikApiEndPoint(
    $name = "search_frontend_libs", 
    $params = [ 
        // Defines the expected params with there defaults.
        "search"     => null, //null indicates no default.
        "limit"      => '20'
    ],
    $filters = [ // Defines filters to apply -> this will modify the params.
        "search"    => BsikValidate::add_procedure("trim")::add_procedure("strchars","A-Z","a-z","0-9","_","-")::create_filter(),
        "limit"     => BsikValidate::add_procedure("type", "number")::create_filter()
    ],
    $validation = [ // Defines Validation rules of this endpoint.
        "search" => BsikValidate::add_cond("required")::add_cond("type","string")::create_rule(),
        "limit"  => BsikValidate::add_cond("type","integer")::create_rule()
    ],
    //The method to execute -> has Access to BsikApi
    function(BsikApi $Api, array $args) {
        $command = sprintf('npm search --no-color --json --long "%s";', $args["search"]);
        $Api->register_debug("command", $command);
        try {
            if ($exec_result = shell_exec($command)) {
                $Api->register_debug("shell_result", $exec_result);
                if ($exec_result = json_decode($exec_result, true)) {
                    $exec_result = array_slice($exec_result, 0, $args["limit"]);
                    $Api->update_answer_status(200);
                    $Api->request->answer->data = $exec_result;
                } else {
                    throw new Exception("npm search command failed expected json output.", E_NOTICE);
                }
            } else {
                $Api->logger->error("NPM search exec failed silently.", ["in" => "libs/"."search_frontend_libs", "command" => $command]);
                $Api->update_answer_status(500, "exec");
                $Api->request->answer->data = [];
            }
        } catch(Exception $e) {
            $Api->logger->error("NPM search exec failed critical.", [
                "in" => "libs/"."search_frontend_libs", 
                "command" => $command,
                "error"   => $e->getMessage()
            ]);
            $Api->update_answer_status(500, "internal");
            $Api->request->answer->data = [];
        }
        return true;
    }
));