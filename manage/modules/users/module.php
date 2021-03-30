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
$ModuleBlockRender = function(APage $APage, Admin $Admin = null) {

//Load additional libs:
//$APage->include("head", "js", "link", ["name" => "http://unpkg.com/tableexport.jquery.plugin@1.10.22/tableExport.min.js"]);
//$APage->include("body", "js", "link", ["name" => PLAT_FULL_DOMAIN."/manage/lib/required/bootstrap-table/extensions/export/bootstrap-table-export.js"]);
//$APage->include("head", "js", "link", ["name" => PLAT_FULL_DOMAIN."/manage/lib/js/tableExport.js"]);

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

return $content;

};
