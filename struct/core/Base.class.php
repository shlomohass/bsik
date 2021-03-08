<?php
/******************************************************************************/
// Created by: Shlomi Hassid.
// Release Version : 1.0.1
// Creation Date: date
// Copyright 2020, Shlomi Hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->creation - initial
*******************************************************************************/
require_once "Excep.class.php";
require_once 'Db.class.php';

class Base 
{

    /* Base Static properties:*/
    public static $conf;
    public static MysqliDb $db;
    private static $regex = [
        "filter-none" => '/[^%s]/'
    ];
    /* Get datetime str
     *  @param $w => String
     *  @Default-param: "now-str"
     *  @return String
     *  @Exmaples:
     *      > "now-str" => now in 'Y-m-d H:i:s' format
     *      > "now-mysql" => now in 'Y-m-d H:i:s' format
     *      > Any => runs date() and pass argument
     */
    public static function configure( array $_conf) : void {
        self::$conf = $_conf;
    }
    public static function connect_db() : void {
        self::$db = new MysqliDb(
            self::$conf["db"]['host'], 
            self::$conf["db"]['user'], 
            self::$conf["db"]['pass'], 
            self::$conf["db"]['name'], 
            self::$conf["db"]['port']
        );
    }
    public static function disconnect_db() : void {
        self::$db->disconnect();
    }
    
    /**
     * print_pre
     * useful print variables in a pre container
     * @param  mixed $out = packed values
     * @return void
     */
    public static function print_pre(...$out) {
        print "<pre>";
        foreach ($out as $value) print_r($value);
        print "</pre>";
    }
    
    
    /* A Ui based to handle errors that occurred:
    */
    public static function error_page($code = 0) {
        Base::jump_to_page("error",["ername" => $code],true);
    }
    /* Jump to page by redirect if headers were sent will use a javascript method.
     *  @param $page => String - Page name as used by system
     *  @param $Qparams => Array - Keys as params names and values as value to attach to the URL query string
     *  @param $exit => Boolean - whether to kill the page or not
     *  @Default-params: 
     *      - String "main", 
     *      - [{no query String extra params}],
     *      - Boolean True
     *  @Examples:
     *      > jump_to_page("about", ["v" => 10]) => redirects to the about page with v = 10
    */
    public static function jump_to_page($page = "/", $Qparams = [], $exit = true) {
        $url = Page::$index_page_url."/".
                ($page !== "/" ? urlencode($page)."/" : "").
                (!empty($Qparams) ? "?" : "");
        foreach ($Qparams as $p => $v)
            $url .= "&".urlencode($p)."=".urlencode($v);
        if (headers_sent()) 
            echo '<script type="text/javascript">window.location = "'.$url.'"</script>';
        else
            header("Location: ".$url);
        if ($exit) exit();
    } 
    public static function create_session(array $sessions) {
        foreach ($sessions as $key => $sess) {
            $_SESSION[$key] = $sess;
        }
    }
    public static function delete_session(array $sessions) {
        foreach ($sessions as $sess) {
            if (isset($_SESSION[$sess]))
                unset($_SESSION[$sess]);
        }
    }

    /* Map all files in a folder:
     *  @param $path => String : the path to the dynamic pages folder.
     *  @param $ext => String : the extension.
     *  @return array
    */
    protected function list_files_in(string $path, string $ext = ".php") : array {
        return array_filter(
            scandir($path), function($k) use($ext) { 
                return is_string($k) && self::std_str_ends_with($k, $ext); 
            }
        );
    }

/********************** STRING HELPERS *********************************************/
    /**
     * string_starts_with
     * Check if a string starts with a string
     * 
     * @param  string $haystack
     * @param  string $needle
     * @return bool
     */
    final public static function std_str_starts_with(string $haystack, string $needle) : bool {
        return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
    }
    /**
     * string_ends_with
     * Check if a string ends with a string
     * 
     * @param  string $haystack
     * @param  string $needle
     * @return bool
     */
    final public static function std_str_ends_with(string $haystack, string $needle) : bool {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }    
    /**
     * filter_string
     *
     * @param  string $str
     * @param  mixed $allowed - string or array
     * @return string - filtered string
     */
    final public static function std_str_filter_string(string $str, $allowed = ["A-Z","a-z","0-9"]) : string {
        $regex = is_string($allowed) ? 
            sprintf(self::$regex["filter-none"], $allowed) :
            sprintf(self::$regex["filter-none"], implode($allowed));
        return preg_replace($regex, '', $str);
    }
    /********************** ARRAY HELPERS *********************************************/    
    /**
     * std_arr_get_from
     * return only required keys if defined else a default value
     * @param  array $data - the array with all the data
     * @param  array $keys - keys to return
     * @param  mixed $default - default value if not set
     * @return array - matching keys and there value
     */
    final public static function std_arr_get_from(array $data, array $keys, $default = null) : array {
        $filter = array_fill_keys($keys, $default);
        $merged = array_intersect_key($data, $filter) + $filter;
        ksort($merged);
        return $merged;
    }
    /********************** DATE HELPERS *********************************************/    
    /**
     * std_time_datetime
     * return a time stamp in a pre defined format
     * @param  string $w - the format to use
     * @return mixed -> string or false when error
     */
    final public static function std_time_datetime(string $w = "now-str")
    {
        switch ($w) {
            case "now-str" :
                return date('Y-m-d H:i:s');
            case "now-mysql" :
                return date('Y-m-d H:i:s');
            default:
                return date($w);
        }
    }
}