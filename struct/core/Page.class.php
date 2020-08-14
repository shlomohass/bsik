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
include_once "Base.class.php";

class Page extends Base
{
    private $types = array("page","api");
    private $defalts = array("main-page" => "main",);
    public $dynamic_pages = [];
    static $index_page_url;
    public $request =  array(
        "type"      => "",      // page, api
        "page"      => "",
        "when"      => ""
    );
    public $token =  array(
        "csrf" => "",
        "meta" => ""
    );
    public $includes = array(
        "head"          => array("js" => array(), "css" => array()),
        "body-begin"    => array("js" => array(), "css" => array()),
        "body-end"      => array("js" => array(), "css" => array())
    );
    private $head_meta = array(
        "lang"                  => "",
        "charset"               => "",
        "viewport"              => "",
        "author"                => "",
        "description"           => "",
        "title"                 => "",
        "icon"                  => ""
    );
    private $head_optional_meta = array();
    private $custom_body_tag = "";
    private $storage = array();
    /* Page constructor.
     *  @param $conf => SIK configuration array Used in Base Parent
     *  @Default-params: none
     *  @return none
     *  @Exmaples:
    */
    public function __construct($conf)
    {
        parent::__construct($conf);
        $this->tokenize(); //Tokenize the page.
        $this->request["type"] = $this->request_type(); //Get the request 
        $this->request["page"] = $this->request_page(); //Which page or operation to perform ??
        $this->fill_dynamic_pages($this::$conf["path"]["dynamic_pages"]); //Create a list of all available pages.
        $this::$index_page_url = $this->parse_slash_url_with($this::$conf["path"]["domain"].$this::$conf["path"]["site_base_path"]);
        $this->request["when"] = self::datetime(); //Time stamp for debuging
        $this->head_meta = self::array_extend($this->head_meta, $this::$conf["page"]["meta"]); //Sets the ,eta global defaults
    }
    /* Get and set the type of the page request.
     *  @Default-params: none
     *  @return String
     * 
    */
    private function request_type()
    {
        // TODO: Log the requests given to the server.
        return (isset($_REQUEST["type"]) && in_array($_REQUEST["type"] ,$this->types)) ? $_REQUEST["type"] : $this->types[0];
    }
    /* Get and Set the page requested.
     *  @Default-params: none
     *  @return String
     *
    */  
    private function request_page()
    {
        // TODO: Log the requests given to the server.
        return (isset($_REQUEST["page"]) && ctype_alnum($_REQUEST["page"])) ? $_REQUEST["page"] : $this->defalts["main-page"];
    }
    /* Get and Set the page token If not set create a new one.
     *  @Default-params: none
     *  @return none
    */  
    private function tokenize()
    {
        if (empty($_SESSION['csrf_token']))
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->token["csrf"] = $_SESSION['csrf_token'];
        $this->token["meta"] = "<meta name='csrf-token' content=".$this->token["csrf"].">";
    }
    /* Fill the class pages array ll dynamic pages - traverse the folder.
     *  @param $path => String : the path to the dynamic pages folder.
     *  @Default-params: none
     *  @return none
    */
    private function fill_dynamic_pages($path) {
        // TODO: should we check the files?? maybe a security issue???
        $this->dynamic_pages = array_filter(
            scandir($path), function($k) { 
                return is_string($k) && self::string_ends_with($k, ".php"); 
            }
        );
    }
    /* include libs in the page sections.
     *  @param $place => String - must be: head | body-begin | body-end
     *  @param $type => String - file type to include must be - js | css
     *  @Default-params: none
     *  @return Object(this)
     *  @Example:
     *      > $Page->include("head", "js", "jquery.min.js");
    */
    public function include($place, $type, $file, $add = "") {
        if (!is_string($place) || !isset($this->includes[$place]))
            trigger_error("'Page->include' first argument ($place) is unknown place value", E_PLAT_WARNING);
        if (!is_string($type) || (strtolower($type) !== "js" && strtolower($type) !== "css"))
            trigger_error("'Page->include' second argument ($type) must be a valid type argument - js | css.", E_PLAT_WARNING);
        $link = (self::string_starts_with($file,"//") || self::string_starts_with($file,"http"))
                ? $file : $this->build_url_with(PATH_LIB, $file);
        $this->includes[$place][$type][] = ["link" => $link, "add" => $add];
        return $this;
    }
    /* Set and Gets page html meta tags values.
     *  @param $name => String - the meta tag to use
     *  @param $set => Mixed - False|String if false will GET if string will set.
     *  @Default-params: $set = False
     *  @return String | Object(this)
     *  @Examples:
     *      > $Page->meta("author", "SIK Framework");
    */
    public function meta($name, $set = false) {
        if (!isset($this->head_meta[$name]))
            trigger_error("'Page->meta()' you must use a valid meta type.", E_PLAT_WARNING);
        if (!$set) return $this->head_meta[$name];
        $this->head_meta[$name] = $set;
        return $this;
    }
    public function op_meta($name = false, $set = false) {
        if (!$name) {
            return $this->head_optional_meta;
        }
        if (!$set) {
            return (isset($this->head_optional_meta[$name]))?$this->head_optional_meta[$name]:"";
        }
        $this->head_optional_meta[$name] = $set;
        return $this;
    }
    /* Set and Gets a custom body tag <body *******>.
     *  @param $set => MIXED - String | False
     *  @Default-params: false
     *  @return MIXED - String | Object(this)
    */
    public function body_tag($set = false) {
        if (!$set) return $this->custom_body_tag;
        $this->custom_body_tag = $set;
        return $this;
    }
    /* Stich a url together for normalizing urls.
     *  @param $path => String - only the traverse folders
     *  @param $file => String - filename
     *  @Default-params: none
     *  @return String
     *  @Examples:
     *      > $Page->build_url_with("/dir/img/", "dom.png");
    */
    public function build_url_with($path, $file) {
        return str_replace('\\', '/', URL_DOMAIN.$path.$file);
    }
    public function parse_slash_url_with($url) {
        return str_replace('\\', '/', $url);
    }
    /* Storage used to save data and handle it safely.
     *  @param $name => String
     *  @param $data => Mixed
     *  @param $protect => Boolean
     *  @Default-params: protect - true
     *  @return Boolean
     *  @Examples:
     *      > $Page->store("test value", "dom.png");
    */
    public function store($name, $data, $protect = true) {
        if ($protect && isset($this->storage[$name])) {
            trigger_error("'Page->store' you are trying to override a protected storage member", E_PLAT_WARNING);
            return false;
        }
        $this->storage[$name] = $data;
        if ($data === false || $data === null) return false;
        return true;
    }
    /* get method is used to retrieve stored data.
     *  @param $name => Boolean|String // if True return the entire storage array, otherwise return by name.
     *  @Default-params: None
     *  @return Mixed
     *  @Examples:
     *      > $Page->get(true);
     *      > $Page->get("key-name");
    */
    public function get($name) {
        return $name === true ? $this->storage : $this->storage[$name];
    }
}