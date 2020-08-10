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
    /* Get datetime str
     *  @param $w => String
     *  @Default-param: "now-str"
     *  @return String
     *  @Exmaples:
     *      > "now-str" => now in 'Y-m-d H:i:s' format
     *      > "now-mysql" => now in 'Y-m-d H:i:s' format
     *      > Any => runs date() and pass argument
     */
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
}