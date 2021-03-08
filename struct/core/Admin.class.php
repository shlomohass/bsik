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
require_once "Trace.class.php";
require_once "Base.class.php";

class Admin extends Base {
    
    /** Class Properties
     *
     */
    public $is_signed       = false;
    public $levels          = [];
    public $priv            = (object) array(
        "level"      => "",
        "users"      => 0,
        "content"    => 0,
        "admin"      => 0,
        "install"    => 0
    );
    public $admin_data      = false;

    /** Constructor
     * 
     * @param array $conf
     */
    public function __construct() {
        $this->levels = self::$db->map("id")->get('admin_levels'); 
    }

    public function initial_admin_login_status($gClient) {
        //First check if already signed:
        $defined = self::std_arr_get_from($_SESSION, ["adminid", "admintoken"]);
        if ($defined['adminid'] && $defined['admintoken']) {
            //User has access token
            $this->admin_data = self::$db->where("id", $_SESSION['adminid'])
                                         ->where("e_token", $defined['admintoken'])
                                         ->getOne("admins");
            //Load privileges:
            if (!empty($this->admin_data) && isset($this->admin_data["level"])) {
                $this->priv->level      = $this->levels[$this->admin_data["id"]]["level"];
                $this->priv->users      = $this->levels[$this->admin_data["id"]]["priv_users"] ? true : false;
                $this->priv->content    = $this->levels[$this->admin_data["id"]]["priv_content"] ? true : false;
                $this->priv->admin      = $this->levels[$this->admin_data["id"]]["priv_admin"] ? true : false;
                $this->priv->install    = $this->levels[$this->admin_data["id"]]["priv_install"] ? true : false;
            } else {
                $this->delete_session(["adminid", "admintoken"]);
            }
        }
        //TODO: log admin is active into DB.
        //TODO: Update Admin Last Seen:
        // Check if this user exists and is active
        $this->is_signed = ($this->admin_data["user_account_status"] ?? -1 === 0) ? true : false;
        return $this->is_signed;
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
                if (($_SERVER['HTTP_X_FORWARDED_FOR'] ?? false) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (($_SERVER['HTTP_CLIENT_IP'] ?? false) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        /* SH: added - 2021-03-03 => Check if its an error - why? not equal */
        if ($ip === "127.0.0.1") 
            $ip = @file_get_contents("http://ipecho.net/plain");
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