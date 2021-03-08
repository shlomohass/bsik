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
$CoreBlockRender = function($Page, array $Values = []) {

    // Intellisense support
    if (!isset($Page)) $Page = new Page();
    
    //Core Block Defaults:
    $block_defaults = [
        "isadmin" => false,
        "folder"  => "manage"
    ];

    //Extend settings:
    $block_setting = array_merge_recursive($block_defaults, $Values);

    //Top Includes:
    // ob_start();
    //     $Page->render_libs("js", "body");
    // $end_includes = ob_get_clean();
    $admin_pannel = $Page->build_url_with($block_setting["folder"]);

$content = <<<HTML
        <!-- START : body includes -->
        <div class='siktec-admin-pannel-widget'>
            <a href="$admin_pannel">Admin Pannel</a>
        </div>
        <!-- END : body includes -->
HTML;

return $block_setting["isadmin"] ? $content : "";

};
