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
        "doc-type"   => "html",
        "html-attr" => "data-docby='BSIK Platfom'"
    ];

    //Extend settings:
    $block_setting = array_merge_recursive($block_defaults, $Values);

    //Extended meta:
    $ex_meta = implode(PHP_EOL, $Page->additional_meta).PHP_EOL;

    //Top Includes:
    ob_start();
        $Page->render_libs("css", "head");
        $Page->render_libs("js", "head");
    $top_includes = ob_get_clean();

    //Body css include:
    ob_start();
        $Page->render_libs("css", "bold");
    $body_includes = ob_get_clean();
    
$content = <<<HTML

<!doctype {$block_setting['doc-type']}>
<html {$block_setting['html-attr']}> 
    <head>
        <meta charset="{$Page->meta('charset')}" />
        <meta name="viewport"               content="{$Page->meta('viewport')}" />
        <meta name="author"                 content="{$Page->meta('author')}" />
        <meta name="description"            content="{$Page->meta('description')}" />
        <meta http-equiv="X-UA-Compatible"  content="IE=7" />
        {$Page->token["meta"]}
        {$Page->render_favicon(PLAT_FULL_DOMAIN."/img/fav")}
        $ex_meta
        <title>{$Page->meta("title")}</title>
        <link rel="icon" href="{$Page->meta('icon')}" />
        <!-- START : Head includes -->
        $top_includes
        <!-- END : Head includes -->
    </head>
    <body {$Page->body_tag()} >
        <!-- START : Body includes -->
        $body_includes
        <!-- END : Body includes -->

HTML;

return $content;

};
