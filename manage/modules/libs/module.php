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
            <h2 class='module-title'>Search on 'npm'</h2>
            <div class='col-12 npm-search-results sik-form-init'>
                <form>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" aria-label=".form-control-lg">
                        <input class="form-control" type="text" placeholder="Default input" aria-label="default input">
                        <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" aria-label=".form-control-sm">
                    </div>
                    <div class="mb-3">
                        <input class="form-control" type="text" placeholder="Readonly input here..." aria-label="readonly input example" readonly>
                        <input class="form-control" type="text" placeholder="Disabled input" aria-label="Disabled input example" disabled>
                        <input class="form-control" type="text" placeholder="Disabled readonly input" aria-label="Disabled input example" disabled readonly>
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Default file input example</label>
                        <input class="form-control" type="file" id="formFile">
                    </div>
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Multiple files input example</label>
                        <input class="form-control" type="file" id="formFileMultiple" multiple>
                    </div>
                    <div class="mb-3">
                        <label for="formFileDisabled" class="form-label">Disabled file input example</label>
                        <input class="form-control" type="file" id="formFileDisabled" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Small file input example</label>
                        <input class="form-control form-control-sm" id="formFileSm" type="file">
                    </div>
                    <div class="mb-3">
                        <label for="formFileLg" class="form-label">Large file input example</label>
                        <input class="form-control form-control-lg" id="formFileLg" type="file">
                    </div>
                    <div class="mb-3">
                        <label for="exampleColorInput" class="form-label">Color picker</label>
                        <input type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c" title="Choose your color">
                    </div>
                    <div class="mb-3">
                        <label for="exampleDataList" class="form-label">Datalist example</label>
                        <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Type to search...">
                        <datalist id="datalistOptions">
                            <option value="San Francisco">
                            <option value="New York">
                            <option value="Seattle">
                            <option value="Los Angeles">
                            <option value="Chicago">
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" aria-label="Default select example" disabled>
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
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
