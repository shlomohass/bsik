<?php

/******************************  Gaurd  *****************************/
if (!isset($Page)) {
    include_once(PATH_REQUIRED_PAGES . "error.php?p=main&code=10");
    die();
}

/******************************  Set Includes  *****************************/
$Page->include("head",     "css",  "//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic")
    ->include("head",      "css",  "import/normalize/normalize.css")
    ->include("head",      "css",  "import/milligram/milligram.css")
    ->include("head",      "css",  "css/components.css")
    ->include("head",      "css",  "css/global.css")
    ->include("head",      "css",  "css/main.css")
    ->include("head",      "js",   "https://kit.fontawesome.com/c7133f55bd.js")
    ->include("head",      "js",   "import/jquery/jquery-3.5.1.min.js")
    ->include("body-end",  "js",   "import/validate/validate.min.js")
    ->include("body-end",  "js",   "js/components.js")
    ->include("body-end",  "js",   "js/global.js")
    ->include("body-end",  "js",   "js/main.js");

Trace::add_trace("Loaded page includes", __FILE__, $Page->includes);

/******************************  Prepare data   *****************************/


/******************************  Load head      *****************************/
$Page->meta("lang", "he")
    ->meta("title", "SIK Framework")
    ->meta("description", "PHP made simple the correct way to build fast and powerfull PHP web apps")
    ->body_tag("style=''");

Trace::add_trace("Loaded page head meta", __FILE__, $Page->op_meta());
require_once PATH_BLOCKS . "basic-header.php"; //Load the basic header block
Trace::add_trace("Loaded page head", __FILE__);

/******************************  Set Top Nav       *****************************/
require_once PATH_BLOCKS . "top-nav.php"; //Load the basic header block
Trace::add_trace("Loaded page top nav", __FILE__);
/******************************  Set Side Nav       *****************************/
require_once PATH_BLOCKS . "side-nav.php"; //Load the basic header block
Trace::add_trace("Loaded page side nav", __FILE__);

/******************************  Body Html      *****************************/
?>
<section class="container add-background add-padding stretch" id="examples">
    <h5 class="title">שולחן עבודה</h5>
    <p>You can view more examples of using Milligram.</p>
    <p><a href="<?php print $User->get_g_signup_url(); ?>">Sign In Google</a></p>
    <p>
        <ul>
            <li><a href="https://milligram.github.io/#getting-started" title="Getting Started">Getting Started</a></li>
            <li><a href="https://milligram.github.io/#typography" title="Typography">Typography</a></li>
            <li><a href="https://milligram.github.io/#blockquotes" title="Blockquotes">Blockquotes</a></li>
            <li><a href="https://milligram.github.io/#buttons" title="Buttons">Buttons</a></li>
            <li><a href="https://milligram.github.io/#lists" title="Lists">Lists</a></li>
            <li><a href="https://milligram.github.io/#forms" title="Forms">Forms</a></li>
            <li><a href="https://milligram.github.io/#browser-support" title="Browser Support">Browser Support</a></li>
        </ul>
    </p>
</section>
<div class="container  add-padding stretch">
  <div class="row">
    <div class="column  add-background">.column</div>
    <div class="column add-background">.column</div>
    <div class="column add-background">.column</div>
    <div class="column add-background">.column</div>
  </div>

  <div class="row">
    <div class="column">.column</div>
  </div>

</div>

<?php
Trace::add_trace("Added page HTML", __FILE__);

/******************************  Load Footer    *****************************/
require_once PATH_BLOCKS . "basic-footer.php"; //Load the basic header block
Trace::add_trace("Loaded page Footer", __FILE__);