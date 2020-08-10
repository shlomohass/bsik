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
if (!isset($Page)) { include_once(PATH_REQUIRED_PAGES."error.php?p=main&code=10"); die(); }

?>
<!-- START : Top Navigation Bar -->
<header class="nav-wrap rem-padding rem-margin">
    <nav class="main-nav bar default">
        <a href="#" class="nav-logo">
            <img src="img/logo.png" />
        </a>
        <ul class="nav-list">
            <li class="nav-static">לינק 1</li>
            <li class="nav-static">לינק 2</li>
            <li class="nav-static">לינק 3</li>
            <li class="nav-toggle noselect">
                <div class="nav-ham-icon" id="nav-icon3">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul class="nav-menu">
                    <li><a href="#">לינק 1</a></li>
                    <li><a href="#">לינק 2</a></li>
                    <li><a href="#">לינק 3</a></li>
                    <li><a href="#">לינק 4</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<!-- END : Top Navigation Bar -->