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
$CoreBlockRender = function(APage $APage, array $Values = []) {

    //Core Block Defaults:
    $block_defaults = [

    ];

    //Extend settings:
    $block_setting = array_merge_recursive($block_defaults, $Values);

    $menu_tpl = "<li class='menu-entry %s' data-menuact='%s' title='%s'><i class='%s'></i>%s%s</li>".PHP_EOL;
    $build_list = "<ul class='admin-menu'>".PHP_EOL;
    foreach ($APage->menu as $entry) {
        $sub_menu = "<ul class='entry-sub-menu'>".PHP_EOL;
        $parts = $APage::std_arr_get_from($entry, ["text", "title", "icon", "action", "sub"], "");
        //If sub menu is defined?
        if (!empty($parts["sub"])) {
            foreach ($parts["sub"] as $sub) {
                $sub_parts = $APage::std_arr_get_from($sub, ["text", "title", "icon", "action"], "");
                $sub_menu .= sprintf($menu_tpl,
                    "", 
                    $sub_parts["action"], 
                    $sub_parts["title"], 
                    $sub_parts["icon"], 
                    $sub_parts["text"],
                    ""
                );
            }
            $sub_menu .= "</ul>".PHP_EOL;
        } else {
            $sub_menu = "";
        }
        //Build Entry:
        $build_list .= sprintf($menu_tpl,
            !empty($sub_menu) ? "has-submenu" : "", 
            $parts["action"], 
            $parts["title"], 
            $parts["icon"], 
            $parts["text"],
            $sub_menu
        );
    }
    $build_list .= "</ul>";
    
$content = <<<HTML
    <!-- START: DYNAMIC SIDE MENU -->
    $build_list
    <!-- END: DYNAMIC SIDE MENU -->
HTML;

return $content;

};
