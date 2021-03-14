<?php
/******************************  intellisense  *****************************/
if (!isset($APage)) $APage = new APage();
if (!isset($Admin)) $Admin = new Admin();

/******************************  Guard  *****************************/
if (!isset($conf)) {
    include_once PLAT_PATH_MANAGE."/error/?p=main&code=10";
    die();
}

/******************************  Set Meta - required  *****************************/
$APage->meta("lang",         $APage->settings["lang"] ?? "en")
      ->meta("charset",      $APage->settings["charset"] ?? "utf8")
      ->meta("title",        $APage->settings["title"] ?? "")
      ->meta("author",       $APage->settings["author"] ?? "")
      ->meta("description",  $APage->settings["desc"] ?? "");
Trace::add_trace("Required META set done.", __FILE__.__LINE__);

/******************************  Set Meta - optional  *****************************/
foreach($APage->settings["addmeta"] ?? [] as $opm) 
    $APage->op_meta($opm);
Trace::add_trace("Optional META extend done.", __FILE__.__LINE__, "Total: ".count($APage->settings["addmeta"] ?? []));

/******************************  Set Body tag  *****************************/
/* SH: added - 2021-03-06 => connect to cms */
$APage->body_tag("style=''");

/******************************  Set Includes  *****************************/
$loaded_libs = $APage->load_libs($global = true);
// //$APage->include("end", "css", "path", ["name" => "/test/files.js"]);
// Trace::add_trace("loaded template & page libs", __FILE__.__LINE__, "Loaded: ".$loaded_libs);
// $imported_libs = $APage->import_defined_libs();
// Trace::add_trace("Imported libs from DB", __FILE__.__LINE__, "Not found: ".count($imported_libs), $imported_libs);
// //$APage::print_pre($APage->includes);



/******************************  Set Side Menu  *****************************/
$APage->load_menu();
Trace::add_trace("Parsed defined menu entries ", __FILE__.__LINE__);

/******************************  Render Page  *****************************/
//Build html / Head / Meta / includes:
require_once PLAT_PATH_MANAGE.DS."pages".DS."header.php";
$doc_head = $CoreBlockRender($APage, [], false);
Trace::add_trace("Loaded & Render Header structure", __FILE__.__LINE__);

//Close document + bottom includes:
require_once PLAT_PATH_MANAGE.DS."pages".DS."footer.php";
$doc_end =  $CoreBlockRender($APage, [], false);
Trace::add_trace("Loaded & Render End of document structure", __FILE__.__LINE__);

$doc_admin_bar = <<<HTML
<div class="container-fluid">
    <div class="admin-bar">
        Admin Bar
    </div>
</div>
HTML;

//Side Menu:
require_once PLAT_PATH_MANAGE.DS."pages".DS."menu.php";
$doc_side_menu = $CoreBlockRender($APage, [], false);
Trace::add_trace("Loaded & Render side-menu structure", __FILE__.__LINE__);


$doc_tpl = <<<HTML
    %s
    <div class="container-fluid p-0">
        <div class="container-bar">%s</div>
        <div class="content-wrapper">
            <div class="container-side-menu">%s</div>
            <div class="container-module">%s</div>
        </div>
        <div class="container-footer">%s</div>
    </div>
    %s
HTML;

printf($doc_tpl,
    $doc_head,
    "Admin Bar",
    $doc_side_menu,
    "Content<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>content",
    "Footer",
    $doc_end
);