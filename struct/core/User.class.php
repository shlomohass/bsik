<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 1.1
// Creation Date: 06/04/2020
// Copyright 2020, shlomo hassid.
/******************************************************************************/
/*****************************      DEPENDENCE      ***************************/

/******************************************************************************/
/*****************************      Changelog       ****************************
 1.0: initial
*******************************************************************************/

class User {
    
    /** Class Properties
     *
     */
    public $gSignUrl = "";
    public $userIsSigned = false;
    /** Contructor
     * 
     * @param array $conf
     */
    public function __construct( &$conf, &$Db ) {
        Trace::add_trace('construct class',__METHOD__);
        $this->roles = $Db->get('roles'); 
    }
    
    public function get_g_signup_url() {
        return filter_var($this->gSignUrl, FILTER_SANITIZE_URL);
    }

    public function initial_user_login_status($gClient) {
        //First check if allready signed:
        if (isset($_SESSION['usertoken'])) {
            // TODO: make sign in types globals and managed 
            if(isset($_SESSION["userlogintype"]) && $_SESSION["userlogintype"] == "g") { 
                $gClient->setAccessToken($_SESSION['usertoken']);
                if ($gClient->getAccessToken()) { //User is logged...
                    //$userData = $objRes->userinfo->get();
                    if(!empty($userData)) {
                        //insert data into database
                    }
                    return true;
                } else { //Google token is expired or invalid or canceled
                    return false;
                }
            } elseif(isset($_SESSION["userlogintype"]) && $_SESSION["userlogintype"] == "e") {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}