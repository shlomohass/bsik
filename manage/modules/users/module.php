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

$users_table = $APage->render_dynamic_table(
    "users-dynamic-table", 
    $APage::$conf["path"]["base"]."/manage/api/users/",
    "users", 
    [
        "id"            => "Item named Id", 
        "first_name"    => "Item named Name", 
        "email"         => "Item named Price"
    ],
    [
        "search"     => true,
        "sort"       => true,
        "pagination" => true
    ]
);

$content = <<<HTML

<div class='container'>
    <div class='row'>
        <div class='col-12'>
            {$users_table}
        </div>
    </div>
</div>

HTML;

return $content;

};
