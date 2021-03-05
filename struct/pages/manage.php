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
/******************************  Gaurd  *****************************/
if (!isset($Page)) {
    include_once(PATH_REQUIRED_PAGES . "error.php?p=main&code=10");
    die();
}

/******************************  Set Includes  *****************************/
$Page->include("head",      "css",  "import/normalize/normalize.css")
    ->include("head",       "css",  "import/bootstrap/css/bootstrap-grid.css")
    ->include("head",       "css",  "css/manage.css")
    ->include("head",       "js",   "https://kit.fontawesome.com/c7133f55bd.js")
    ->include("head",       "js",   "import/jquery/jquery-3.5.1.min.js")
    ->include("head",       "js",   "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.2.6/gsap.min.js")
    ->include("body-end",   "js",   "js/manage.js");

Trace::add_trace("Loaded page includes", __FILE__, $Page->includes);

/******************************  Prepare data   *****************************/


/******************************  Load head      *****************************/
$Page->meta("lang", "en")
    ->meta("title", "SIKDEV Framework Manage Module")
    ->meta("description", "PHP made simple the correct way to build fast and powerfull PHP web apps")
    ->meta("direction", "ltr")
    ->body_tag("class='dashboard' style=''");

Trace::add_trace("Loaded page head meta", __FILE__, $Page->meta("title"), $Page->meta("description"));
require_once PATH_BLOCKS . "basic-header.php"; //Load the basic header block
Trace::add_trace("Loaded page head", __FILE__);

/******************************  Set Top Nav       *****************************/
require_once PATH_BLOCKS . "manage-top-nav.php"; //Load the basic header block
Trace::add_trace("Loaded page top nav", __FILE__);
/******************************  Set Side Nav       *****************************/
require_once PATH_BLOCKS . "manage-side-nav.php"; //Load the basic header block
Trace::add_trace("Loaded page side nav", __FILE__);

/******************************  Body Html      *****************************/
?>
<div class="container_wrap">
    <article class="container-fluid no-gutters content">
        <div class="row row-cols-1">
            <h2 class="col content_title">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </h2>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col mb-4">
                <div class="card">
                    <div class="card__title">
                        <i class="fa fa-users" aria-hidden="true"></i> Users
                    </div>
                    <div class="card__content">
                        <div class="big-text">2500</div>
                        <div class="small-text">
                            <span class="green-text">
                                <i class="fa fa-caret-up" aria-hidden="true"></i> 4%
                            </span>
                            from last week
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card">
                    <div class="card__title">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        Page Views
                    </div>
                    <div class="card__content">
                        <div class="big-text green-text">
                            424,892
                        </div>
                        <div class="small-text">
                            <span class="green-text">
                                <i class="fa fa-caret-up" aria-hidden="true"></i> 4%
                            </span>
                            from last week
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card">
                    <div class="card__title">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        Avg. Session Duration
                    </div>
                    <div class="card__content">
                        <div class="big-text yellow-text">
                            00:03:20
                        </div>
                        <div class="small-text">
                            <span class="yellow-text">
                                <i class="fa fa-caret-up" aria-hidden="true"></i> 4%
                            </span>
                            from last week
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card">
                    <div class="card__title">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        Bounce Rate
                    </div>
                    <div class="card__content">
                        <div class="big-text red-text">
                            71.46%
                        </div>
                        <div class="small-text">
                            <span class="red-text">
                                <i class="fa fa-caret-down" aria-hidden="true"></i> 4%
                            </span>
                            from last week
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
<?php
Trace::add_trace("Added page HTML", __FILE__);

/******************************  Load Footer    *****************************/
require_once PATH_BLOCKS . "basic-footer.php"; //Load the basic header block
Trace::add_trace("Loaded page Footer", __FILE__);
