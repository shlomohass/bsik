/******************************************************************************
// Created by: Shlomo Hassid.
// Release Version : 1.0.1
// Creation Date: 22/05/2020
// Copyright 2020, Shlomo Hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->initial, creation
*******************************************************************************/

/******************************  Hamburder - action  *****************************/
$('.nav-toggle').click(function() {
    if ($(this).children('.nav-ham-icon').hasClass("open")) {
        $(this).children(".nav-menu").slideUp("fast");
    } else {
        $(this).children(".nav-menu").slideDown("fast");
    }
    $(this).children('.nav-ham-icon,.nav-menu').toggleClass('open');
});

/******************************  Signup / Signin - Modal action  *****************************/
(function($) {
    //Toggle between the two inner views (Signin / Signup):
    $('#signup,#signin').click(function(e) {
        e.preventDefault();
        $this = $(this);
        $signin = $this.closest(".modal-dialog").children(".signin-form");
        $signup = $this.closest(".modal-dialog").children(".signup-form");
        if ($signin.is(":visible")) {
            $signin.slideUp();
            $signup.slideDown();
        } else {
            $signin.slideDown();
            $signup.slideUp();
        }
    });

}(jQuery));