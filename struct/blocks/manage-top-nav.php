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
<header class="header">
    <section class="header__wrapper">
        <a class="brand" href="index.html">
            <img class="brand__img" src="img/logo-manage.png" />
        </a>
        <ul class="menu float-right">
            <li class="menu__item">
                <a class="menu__link" href="#popover-grid" data-popover="">Docs</a>
            </li>
            <li class="menu__item">
                <a class="menu__link" href="#popover-support" data-popover="">Support</a>
            </li>
        </ul>

    </section>
</header>