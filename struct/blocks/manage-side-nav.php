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


/******************************  Load some data  *****************************/


?>
<aside class="aside">
    <canvas id="demo-canvas"></canvas>
    <nav class="nav" id="add-background-nav">  
        <div class="nav-label">
            General
        </div>
        <ul class="nav-list">
            <li>
                <a class="is-active" href="#">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
        <div class="nav-label">
            Administration
        </div>
        <ul class="nav-list">
            <li>
                <a href="form.html">
                    <i class="fas fa-pen-square"></i>
                    <span>Forms</span>
                </a>
            </li>
            <li>
                <a href="ui-element.html">
                    <i class="fas fa-desktop"></i>
                    <span>UI Elements</span>
                </a>
            </li>
            <li>
                <a href="table.html">
                    <i class="fas fa-table"></i>
                    <span>Tables</span>
                </a>
            </li>
            <li>
                <a href="presentation.html">
                    <i class="fas fa-chart-bar"></i>
                    <span>Presentations</span>
                </a>
            </li>
            <li>
                <a class="" href="#">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <ul>
                    <li>
                        <a href="#"><i class="fas fa-user"></i> <span>Account</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-user-secret"></i> <span>Privilege</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-wrench"></i> <span>Additional</span></a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="nav-label">
            Live On
        </div>
        <ul class="nav-list">
            <li>
                <a href="#">
                    <i class="fas fa-bug"></i>
                    <span>Additional Pages</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-puzzle-piece"></i>
                    <span>Extras</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-laptop"></i>
                    <span>Landing Page</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>