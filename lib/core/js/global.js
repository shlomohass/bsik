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

    //Validation process:
    var signupValidationConstraints = {
        username: {
            presence: { message: "^required" },
            email: { message: "^invalid-email" }
        },
        password: {
            presence: { message: "^required" },
            length: { minimum: 8, message: "^length" }
        },
        passwordverify: {
            presence: { message: "^required" },
            equality: { attribute: "password", message: "^equality" }
        },
        firstname: {
            presence: { message: "^required" },
            length: { minimum: 2, maximum: 25, message: "^length" }
        },
        lastname: {
            presence: { message: "^required" },
            length: { minimum: 2, maximum: 25, message: "^length" },
        }
    };
    var signUpForm = document.querySelector("form#normal-signup");
    signUpForm.addEventListener("submit", function(e) {
        e.preventDefault();
        //Reset Errors:
        $(signUpForm).find(".login-validate-error").hide();
        $(signUpForm).find(".error-field").removeClass("error-field");
        //Validate the Form:
        var errors = validate(signUpForm, signupValidationConstraints);
        if (errors) {
            //Has Errors:
            for (var field in errors) {
                if (errors.hasOwnProperty(field)) {
                    var $inputEle = $(signUpForm).find("[name='" + field + "']");
                    $inputEle.addClass("error-field");
                    $inputEle.prev("label").children("span.validate-" + errors[field][0]).show();

                }
            }
        } else {
            //All Good:
            console.log("goood");
        }
    });
}(jQuery));