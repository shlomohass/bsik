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

class User extends Base {
    
    /** Class Properties
     *
     */
    public $gSignUrl        = "";
    public $userIsSigned    = false;
    private $DBLink         = null;
    /** Contructor
     * 
     * @param array $conf
     */
    public function __construct( &$conf, &$DB ) {
        parent::__construct($conf);
        Trace::add_trace('construct class',__METHOD__);
        $this->DBLink = $DB;
        $this->roles = $this->DBLink->get('roles'); 
    }
    /* Get Google Signup Url -> for exposing on the web platform.
     *  @param None
     *  @Default-params: None
     *  @return String
     *  @Exmaples:
     *      > get_g_signup_url() => Returns the Url
    */
    public function get_g_signup_url() {
        return filter_var($this->gSignUrl, FILTER_SANITIZE_URL);
    }
    /* Save G user and if exists will update credentials:
     */
    public function save_g_signup_user($g_token, $gpUserData) {
        Trace::add_trace('Saving/Updating G+ signup user',__METHOD__);
        $checkExists = $this->DBLink->select("users", ["id"], [["email", "=", $gpUserData['email']]]);
        if (empty($checkExists)) { //New Email address = New user
            $Qexec = $this->DBLink->insert_safe( "users", 
                [   "g_token"       => json_encode($g_token),
                    "g_meta"        => json_encode($gpUserData),
                    "g_profile"     => $gpUserData['oauth_uid'],
                    "role"          => "",
                    "first_name"    => $gpUserData['first_name'],
                    "last_name"     => $gpUserData['last_name'],
                    "birth_date"    => $gpUserData['birthday'],
                    "birth_year"    => substr($gpUserData['birthday'], 0, 4),
                    "country"       => "",
                    "gender"        => $gpUserData['gender'],
                    "email"         => $gpUserData['email'],
                    "status"        => 1,
                    "seen_counter"  => 1,
                    "locale"        => $gpUserData['g_locale'],
                    "picture"       => $gpUserData['picture'],
                    "timezone"      => "",
                    "last_seen"     => $this->datetime("now-mysql")
                ]
            );
        } else { //Already a registered user - just update and create cookies:
            //TODO: seen counter update only if last seen is old enough
            $Qexec = $this->DBLink->update( "users", 
                [   "g_token"       => json_encode($g_token),
                    "g_meta"        => json_encode($gpUserData),
                    "g_profile"     => $gpUserData['oauth_uid'],
                    "birth_date"    => $gpUserData['birthday'],
                    "birth_year"    => substr($gpUserData['birthday'], 0, 4),
                    "email"         => $gpUserData['email'],
                    "seen_counter"  => "++1",
                    "locale"        => $gpUserData['g_locale'],
                    "last_seen"     => $this->datetime("now-mysql")
                ]
            );
        }
        if (!$Qexec) {
            //TODO: Test this redirect to error page.
            $this::error_page("g_login_db_error");
        }
        //Handle sessions:
        $this::create_session(["usertoken" => $g_token, "userlogintype" => "g"]);
    }

    public function initial_user_login_status($gClient) {
        //First check if allready signed:
        if (isset($_SESSION['usertoken'])) {
            // TODO: make sign in types globals and managed 
            if(isset($_SESSION["userlogintype"]) && $_SESSION["userlogintype"] == "g") { 
                $gClient->setAccessToken($_SESSION['usertoken']);
                if ($gClient->getAccessToken()) { //User is logged...

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