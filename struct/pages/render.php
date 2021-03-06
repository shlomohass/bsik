<?php
/******************************  intellisense  *****************************/
if (!isset($Page)) $Page = new Page();

/******************************  Guard  *****************************/
if (!isset($conf)) {
    include_once(PATH_REQUIRED_PAGES . "error.php?p=main&code=10");
    die();
}
//$Page::print_pre($Page->definition);


/******************************  Set Meta   *****************************/
// $Page->meta("lang", "en")
//      ->meta("title", "SIK Framework")
//      ->meta("description", "PHP made simple the correct way to build fast and powerfull PHP web apps")
//      ->body_tag("style=''");

/******************************  Set Includes  *****************************/
$loaded_libs = $Page->load_libs($template_libs = true, $page_libs = true);
//$Page->include("end", "css", "path", ["name" => "/test/files.js"]);
Trace::add_trace("loaded template & page libs", __FILE__.__LINE__, "Loaded: ".$loaded_libs);
$imported_libs = $Page->import_defined_libs();
Trace::add_trace("Imported libs from DB", __FILE__.__LINE__, "Not found: ".count($imported_libs), $imported_libs);
//$Page::print_pre($Page->includes);






/******************************  Render Page  *****************************/
$Page->render_libs("css", "head");
$Page->render_libs("js", "head");

// $Page->include("head",     "css",  "//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic")
//     ->include("head",      "css",  "lib/import/normalize/normalize.css")
//     ->include("head",      "css",  "lib/import/milligram/milligram.css")
//     ->include("head",      "css",  "lib/css/components.css")
//     ->include("head",      "css",  "lib/css/global.css")
//     ->include("head",      "css",  "lib/css/main.css")
//     ->include("head",      "js",   "https://kit.fontawesome.com/c7133f55bd.js")
//     ->include("head",      "js",   "lib/import/jquery/jquery-3.5.1.min.js")
//     ->include("body-end",  "js",   "lib/import/validate/validate.min.js")
//     ->include("body-end",  "js",   "lib/js/components.js")
//     ->include("body-end",  "js",   "lib/js/global.js")
//     ->include("body-end",  "js",   "lib/js/main.js");

// Trace::add_trace("Loaded page includes", __FILE__, $Page->includes);

/******************************  Prepare data *****************************/


/******************************  Load head *****************************/
// $Page->meta("lang", "he")
//     ->meta("title", "SIK Framework")
//     ->meta("description", "PHP made simple the correct way to build fast and powerfull PHP web apps")
//     ->body_tag("style=''");

// Trace::add_trace("Loaded page head meta", __FILE__, $Page->op_meta());
// require_once PATH_BLOCKS.DS. "basic-header.php"; //Load the basic header block
// Trace::add_trace("Loaded page head", __FILE__);

// /******************************  Set Top Nav       *****************************/
// require_once PATH_BLOCKS.DS. "top-nav.php"; //Load the basic header block
// Trace::add_trace("Loaded page top nav", __FILE__);
// /******************************  Set Side Nav       *****************************/
// require_once PATH_BLOCKS.DS. "side-nav.php"; //Load the basic header block
// Trace::add_trace("Loaded page side nav", __FILE__);

// /******************************  Body Html      *****************************/
// ?>
<!-- // <section class="container add-background add-padding stretch" id="examples">
//     <h5 class="title">שולחן עבודה</h5>
//     <p>You can view more examples of using Milligram.</p>
//     <p><a href="<?php /*print $User->get_g_signup_url();*/ ?>">Sign In Google</a></p>
//     <p>
//         <ul>
//             <li><a href="https://milligram.github.io/#getting-started" title="Getting Started">Getting Started</a></li>
//             <li><a href="https://milligram.github.io/#typography" title="Typography">Typography</a></li>
//             <li><a href="https://milligram.github.io/#blockquotes" title="Blockquotes">Blockquotes</a></li>
//             <li><a href="https://milligram.github.io/#buttons" title="Buttons">Buttons</a></li>
//             <li><a href="https://milligram.github.io/#lists" title="Lists">Lists</a></li>
//             <li><a href="https://milligram.github.io/#forms" title="Forms">Forms</a></li>
//             <li><a href="https://milligram.github.io/#browser-support" title="Browser Support">Browser Support</a></li>
//         </ul>
//     </p>
// </section>
// <div class="container  add-padding stretch">
//   <div class="row">
//     <div class="column  add-background">.column</div>
//     <div class="column add-background">.column</div>
//     <div class="column add-background">.column</div>
//     <div class="column add-background">.column</div>
//   </div>

//   <div class="row">
//     <div class="column">.column</div>
//   </div>

// </div> -->

<?php
// Trace::add_trace("Added page HTML", __FILE__);

// /******************************  Load Footer    *****************************/
// require_once PATH_BLOCKS.DS. "basic-footer.php"; //Load the basic header block
// Trace::add_trace("Loaded page Footer", __FILE__);