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
    public $user_data       = false;
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
    /* Get roles ID integer of Users based on the role name:
     *  @param $role_name => String the role name to search
     *  @Default-params: None
     *  @return Mixed => False | Integer {False for not found}
     *  @Examples:
     *      > get_role_id("user") => return 2
    */
    public function get_role_id($role_name) {
        if (isset($this->roles) && is_array($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role["role"] === strtolower($role_name)) {
                    return $role["id"];
                }
            }
        }
        return false;
    }
    /* Get roles Name of Users Based on the role id:
     *  @param $role_name => Number the role id to search
     *  @Default-params: None
     *  @return Mixed => False | String {False for not found}
     *  @Examples:
     *      > get_role_id(2) => return "user"
    */
    public function get_role_name($role_id) {
        if (isset($this->roles) && is_array($this->roles)) {
            foreach ($this->roles as $role) {
                if (intval($role["id"]) === intval($role_id)) {
                    return $role["name"];
                }
            }
        }
        return false;
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
        $geo_result = $this->ip_info("Visitor", "location");
        if (empty($checkExists)) { //New Email address = New user
            $Qexec = $this->DBLink->insert_safe( "users", 
                [   "g_access_token"=> $g_token["access_token"],
                    "g_token"       => json_encode($g_token),
                    "g_meta"        => json_encode($gpUserData),
                    "g_profile"     => $gpUserData['oauth_uid'],
                    "role"          => $this->get_role_id("user"),
                    "first_name"    => $gpUserData['first_name'],
                    "last_name"     => $gpUserData['last_name'],
                    "birth_date"    => $gpUserData['birthday'],
                    "birth_year"    => substr($gpUserData['birthday'], 0, 4),
                    "ip_country_name"  => !empty($geo_result) ? $geo_result["country"] : "NULL",
                    "ip_country_code"  => !empty($geo_result) ? $geo_result["country_code"] : "NULL",
                    "ip_continent_name"=> !empty($geo_result) ? $geo_result["continent"] : "NULL",
                    "ip_continent_code"=> !empty($geo_result) ? $geo_result["continent_code"] : "NULL",
                    "ip_geo_object"    => !empty($geo_result) ? $geo_result["full"] : "NULL",
                    "ip_timezone"      => !empty($geo_result) ? $geo_result["timezone"] : "NULL",
                    "gender"        => $gpUserData['gender'],
                    "email"         => $gpUserData['email'],
                    "status"        => 1,
                    "seen_counter"  => 1,
                    "locale"        => $gpUserData['g_locale'],
                    "picture"       => $gpUserData['picture'],
                    "last_seen"     => $this->datetime("now-mysql")
                ]
            );
        } else { //Already a registered user - just update and create cookies:
            //TODO: seen counter update only if last seen is old enough
            $Qexec = $this->DBLink->update( "users", 
                [   "g_access_token"=> $g_token["access_token"],
                    "g_token"       => json_encode($g_token),
                    "g_meta"        => json_encode($gpUserData),
                    "g_profile"     => $gpUserData['oauth_uid'],
                    "birth_date"    => $gpUserData['birthday'],
                    "birth_year"    => substr($gpUserData['birthday'], 0, 4),
                    "email"         => $gpUserData['email'],
                    "seen_counter"  => "++1",
                    "locale"        => $gpUserData['g_locale'],
                    "last_seen"     => $this->datetime("now-mysql"),
                    "ip_country_name"  => !empty($geo_result) ? $geo_result["country"] : "NULL",
                    "ip_country_code"  => !empty($geo_result) ? $geo_result["country_code"] : "NULL",
                    "ip_continent_name"=> !empty($geo_result) ? $geo_result["continent"] : "NULL",
                    "ip_continent_code"=> !empty($geo_result) ? $geo_result["continent_code"] : "NULL",
                    "ip_geo_object"    => !empty($geo_result) ? $geo_result["full"] : "NULL",
                    "ip_timezone"      => !empty($geo_result) ? $geo_result["timezone"] : "NULL"
                ],
                [["id","=",$checkExists[0]["id"]]]
            );
        }
        if (!$Qexec) {
            $this::error_page("g_login_db_error");
        }
        //Handle sessions:
        $this::create_session([
            "usertoken"     => $g_token, 
            "userlogintype" => "g",
            "userid"        => $this->DBLink->lastid()
        ]);
    }

    public function initial_user_login_status($gClient) {
        //First check if already signed:
        if (isset($_SESSION['usertoken']) && isset($_SESSION['userid'])) {
            if(isset($_SESSION["userlogintype"]) && $_SESSION["userlogintype"] == "g") { 
                $gClient->setAccessToken($_SESSION['usertoken']);
                if ($gClient->getAccessToken()) { //User is logged...
                    $this->user_data = $this->DBLink->select(
                        "users", "* ", 
                        [["id", "=", $_SESSION['userid']], 
                        ["g_access_token", "=", $_SESSION['usertoken']["access_token"]]]
                    );       
                } //Google token is expired or invalid or canceled
            } elseif(isset($_SESSION["userlogintype"]) && $_SESSION["userlogintype"] == "e") {
                $this->userIsSigned = true;
            } else {
                $this->userIsSigned = false;
            }
        }
         // Check if this user exists and is active
        if (!empty($this->user_data) && intval($this->user_data[0]["user_account_status"]) === 0) {
            $this->user_data = $this->user_data[0];
            $this->userIsSigned = true;
        }
        //TODO: log user is active into DB.
        //TODO: Update User Last Seen:
        return $this->userIsSigned;
    }
    /* Get the location of user .
     *  @param $ip => String Ip or Visitor -> will detect the IP
     *  @param $purpose => String ->"country", "countrycode", "state", "region", "city", "location", "address"
     *  @param $deep_detect => boolean -> whether to follow HTTP_X_FORWARDED_FOR
     *  @Default-params:
     *      - NULL,
     *      - "location",
     *      - true
     *  @return return
     *  @Exmaples:
     *      echo ip_info("173.252.110.27", "Country"); // United States
     *      echo ip_info("173.252.110.27", "Country Code"); // US
     *      echo ip_info("173.252.110.27", "State"); // California
     *      echo ip_info("173.252.110.27", "City"); // Menlo Park
     *      echo ip_info("173.252.110.27", "Address"); // Menlo Park, California, United States
     *      print_r(ip_info("173.252.110.27", "Location")); // Array ( [city] => Menlo Park [state] => California [country] => United States [country_code] => US [continent] => North America [continent_code] => NA )
     *
    */
    public function ip_info($ip = null, $purpose = "location", $deep_detect = true) {
        $output = null;
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        if ($ip === "127.0.0.1") $ip = @file_get_contents("http://ipecho.net/plain");
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), null, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $json_result = @file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
            $ipdat = @json_decode($json_result);
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode,
                            "timezone"       => @$ipdat->geoplugin_timezone,
                            "full"           => $json_result
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}