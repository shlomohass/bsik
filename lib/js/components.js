/******************************************************************************
// Created by: Shlomo Hassid.
// Release Version : 1.0.1
// Creation Date: 08/08/2020
// Copyright 2020, Shlomo Hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->initial, creation
***********************************************************************************/
"use strict";

/******************************  SIMPLE MODAL v.1.0.0  *****************************/
/*****************************      Changelog       ********************************
1.0.0:
    ->initial, creation
***********************************************************************************/
let compModals = document.querySelectorAll('.open-modal');
let closeModals = document.querySelectorAll('.close-modal');
compModals.forEach(function(modal) {
    modal.addEventListener('click', function(event) {
        //TODO : Validate if the name exists and echo a message if not 
        let whichModal = this.dataset.modalname;
        document.querySelector("." + whichModal).classList.add("opened");
        event.preventDefault();
    }, false);
});
closeModals.forEach(function(closemodal) {
    closemodal.addEventListener('click', function(event) {
        let allModals = document.querySelectorAll(".modal");
        allModals.forEach(function(modal) {
            modal.classList.remove("opened");
        });
        event.preventDefault();
    }, false);
});