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

class Base
{

    /* Base Static properties:*/
    public static $conf;
    /* Get datetime str
     *  @param $w => String
     *  @Default-param: "now-str"
     *  @return String
     *  @Exmaples:
     *      > "now-str" => now in 'Y-m-d H:i:s' format
     *      > "now-mysql" => now in 'Y-m-d H:i:s' format
     *      > Any => runs date() and pass argument
     */
    public function __construct( &$_conf) {
        Trace::add_trace('construct class',__METHOD__);
        $this::$conf = $_conf;
    }
    public static function datetime($w = "now-str")
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
    /* Check if a string starts or ends with a string.
     *  @param $haystack => String - the string to check.
     *  @param $needle => String - the needle to compare
     *  @Default-params: none
     *  @return Boolean
     *  @Exmaples:
     *      > ("House of Sold", "Ho") => True
    */
    public static function string_starts_with($haystack, $needle) {
        return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
    }
    public static function string_ends_with($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
    /* extend arrays like in Jquery.
     *  @param $a => Array - wil be overriden if same key
     *  @param $b => Array - will override $a
     *  @Default-params: none
     *  @return Array
     *  @Exmaples:
    */
    public static function array_extend($a, $b) {
        foreach($b as $k => $v) {
            if( is_array($v) ) {
                if( !isset($a[$k]) ) {
                    $a[$k] = $v;
                } else {
                    $a[$k] = self::array_extend($a[$k], $v);
                }
            } else {
                $a[$k] = $v;
            }
        }
        return $a;
    }
    /* A Ui based to handle errors that occured:
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
    public static function jump_to_page($page = "main", $Qparams = [], $exit = true) {
        $url = Page::$index_page_url."?page=".urlencode($page);
        foreach ($Qparams as $p => $v)
            $url .= "&".urlencode($p)."=".urlencode($v);
        if (headers_sent()) echo '<script type="text/javascript">window.location = "'.$url.'"</script>';
        else header("Location: ".$url);
        if ($exit) exit();
    } 
    public static function create_session($sessions) {
        foreach ($sessions as $key => $sess) {
            $_SESSION[$key] = $sess;
        }
    }
    public static function delete_session($sessions) {
        foreach ($sessions as $sess) {
            if (isset($_SESSION[$sess]))
                unset($_SESSION[$sess]);
        }
    }
}