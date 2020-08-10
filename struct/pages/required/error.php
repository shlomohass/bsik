<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 1.0.1
// Creation Date: 10/05/2020
// Copyright 2020, shlomo hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->Creation - Initial
*******************************************************************************/
if (isset($_GET["ername"])) {
    $error_page = SIKErrorPages::get_by_name($_GET["ername"]);
}

echo "Error - code:".$error_page->code;
echo "<br />";
echo "Error - message:".$error_page->mes;