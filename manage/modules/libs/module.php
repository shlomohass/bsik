<?php
/******************************************************************************/
// Created by: Shlomo Hassid.
// Release Version : 1.0.1
// Creation Date: 17/05/20202
// Copyright 2020, Shlomo Hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->intial, Creation
*******************************************************************************/
require_once PLAT_PATH_VENDOR.DS.'autoload.php'; //Adds intellisense support

$ModuleBlockRender = function(APage $APage, Admin $Admin = null) {

//Load additional libs:
//$APage->include("head", "js", "link", ["name" => "http://unpkg.com/tableexport.jquery.plugin@1.10.22/tableExport.min.js"]);
//$APage->include("body", "js", "link", ["name" => PLAT_FULL_DOMAIN."/manage/lib/required/bootstrap-table/extensions/export/bootstrap-table-export.js"]);
//$APage->include("head", "js", "link", ["name" => PLAT_FULL_DOMAIN."/manage/lib/js/tableExport.js"]);
$content = "";

/******************************  Overview Content  *****************************/
if (in_array($APage->module->which, ["overview", "default"])) {
    //Elements:
    $users_table = $APage->render_dynamic_table(
        "users-dynamic-table",
        "table#users-dynamic-table.table",
        $option_attributes = [
            //"data-toolbar"=>"#toolbar",
            "data-search"=>"true",
            "data-show-refresh"=>"true",
            "data-show-toggle"=>"true",
            "data-show-fullscreen"=>"false",
            "data-show-columns"=>"true",
            "data-show-columns-toggle-all"=>"true",
            //"data-detail-view"=>"true",
            "data-show-export"=>"true",
            "data-click-to-select"=>"true",
            //"data-detail-formatter"=>"detailFormatter",
            "data-minimum-count-columns"=>"2",
            "data-show-pagination-switch"=>"false",
            "data-pagination"=>"true",
            "data-id-field"=>"id",
            "data-page-list"=>"[10, 25, 50, 100, all]",
            "data-show-footer"=>"false",
            "data-side-pagination"=>"server",
            "data-search-align"=>"left"
        ],
        $APage::$conf["path"]["base"]."/manage/api/users/",
        "users", 
        [
            [
                "field"             => "id",
                "title"             => "User ID",
                "sortable"          => true       // Set sort control
            ],
            [
                "field"             => "first_name",
                "title"             => "First Name",
                "sortable"          => true       // Set sort control
            ],
            [
                "field"             => "email",
                "title"             => "Email Address",
                "sortable"          => true       // Set sort control
            ],
            [
                "field"             => 'operate',
                "title"             => 'Actions / Tools',
                "clickToSelect"     => false,
                "events"            => "@js:sikbase.tableOperateEvents", // the function in module js
                "formatter"         => null // Will use dynamic generated formatter only if operations are defined next
            ]
        ],
        [
            ["name" => "like", "title" => "Like me", "icon" => "fa fa-heart"],
            ["name" => "delete", "title" => "Delete me", "icon" => "fa fa-trash"]
        ]
    );

    //Template:
    $content = <<<HTML

    <div class='container'>
        <div class='row'>
            <div class='col-12 sik-form-init'>
                {$users_table}
            </div>
        </div>
    </div>

    HTML;
/******************************  Users roles content  *****************************/
} elseif ($APage->module->which === "install") {

    //$test = Bsik\Forms\SikForm::test();

    $Form = new \Bsik\Forms\BootstrapForms();

    $search_form = "";
    $search_form .= $Form->form_open('search_npm', 'search_npm');
    $search_form .= $Form->input_text(["name" => 'package_name', "string" => "placeholder='enter package name'", "value" => ""]);
    $search_form .= $Form->form_close();

    //Template:
    $content = <<<HTML
<div class='container mt-3'>
    <div class='row'>
        <div class='col-5 sik-form-init'>
            <h2 class='module-title'>Search Libraries And Modules</h2>
            <div class='col-12 sik-form-init'>
                <form>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" class="form-control" id="search-term" aria-describedby="search-help" />
                            <button id="search-npm" type="button" class="btn btn-primary">Search</button>
                        </div>
                        <div id="search-help" class="form-text">Search npm compatible repositories.</div>
                    </div>
                    
                </form>
            </div>
            <div class='col-12 npm-search-results'>
                <p class="text-info text-end fs-6 m-0"><small>Found 0 Libraries / Modules</small></p>
                <div class='wrap-results list-group'>
                    <ul class='list-group list-group-flush'>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Title of library </span>
                            <span class="badge bg-info text-dark">Info</span>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Title of library </span>
                            <span class="badge bg-info text-dark">Info</span>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Title of library </span>
                            <span class="badge bg-info text-dark">Info</span>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Title of library </span>
                            <span class="badge bg-info text-dark">Info</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class='col-7 sik-form-init'>
            <pre>
                compiler
            </pre>
        </div>
    </div>
</div>
HTML;

} else {
    /******************************  Unknown menu entry throw  *****************************/
    throw new Exception("Requested of module menu which is not recognized [{$APage->module->which}].", E_NOTICE);
}

return $content;

};
