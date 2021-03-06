<?php
/******************************  intellisense  *****************************/
if (!isset($Page)) $Page = new Page();

/******************************  Guard  *****************************/
if (!isset($conf)) {
    include_once(PATH_REQUIRED_PAGES . "error.php?p=main&code=10");
    die();
}
//$Page::print_pre($Page->definition);


/******************************  Set Meta - required  *****************************/
$Page->meta("lang", $Page->settings["lang"] ?? "en")
     ->meta("charset", $Page->settings["charset"] ?? "utf8")
     ->meta("title", $Page->settings["title"] ?? "")
     ->meta("author", $Page->settings["author"] ?? "")
     ->meta("description", $Page->settings["desc"] ?? "");
Trace::add_trace("Required META set done.", __FILE__.__LINE__);

/******************************  Set Meta - optional  *****************************/
foreach($Page->settings["addmeta"] ?? [] as $opmeta) 
    $Page->op_meta($opmeta);
Trace::add_trace("Optional META extend done.", __FILE__.__LINE__, "Total: ".count($Page->settings["addmeta"] ?? []));

/******************************  Set Body tag  *****************************/
/* SH: added - 2021-03-06 => connect to cms */
$Page->body_tag("style=''");

/******************************  Set Includes  *****************************/
$loaded_libs = $Page->load_libs($template_libs = true, $page_libs = true);
//$Page->include("end", "css", "path", ["name" => "/test/files.js"]);
Trace::add_trace("loaded template & page libs", __FILE__.__LINE__, "Loaded: ".$loaded_libs);
$imported_libs = $Page->import_defined_libs();
Trace::add_trace("Imported libs from DB", __FILE__.__LINE__, "Not found: ".count($imported_libs), $imported_libs);
//$Page::print_pre($Page->includes);






/******************************  Render Page  *****************************/
//Build html / Head / Meta / includes:
require_once PLAT_PATH_BLOCKS.DS."load-header.php";
print $CoreBlockRender($Page);
Trace::add_trace("Loaded & Render Header structure", __FILE__.__LINE__);



//Render Platform Trace:
Trace::expose_trace();


//Close document + bottom includes:
require_once PLAT_PATH_BLOCKS.DS."load-footer.php";
print $CoreBlockRender($Page);