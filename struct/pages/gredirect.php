<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 1.0.1
// Creation Date: 10/08/2020
// Copyright 2020, shlomo hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->Creation, initial.
*******************************************************************************/
/******************************  Guard  *****************************/
if (!isset($Page)) {
    include_once(PATH_REQUIRED_PAGES . "error.php?p=main&code=10");
    die();
}

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    try {
        $g_token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
        $gClient->setAccessToken($g_token['access_token']);
        $_SESSION['usertoken'] = $gClient->getAccessToken(); 
    }
    catch (\Google_Service_Exception $e) {
        Page::error_page("g_login_internal_error");
    }
  // get profile info and birthday:
  $gpUserProfile = $google_oauth->userinfo_v2_me->get();
  $gPlusService = new Google_Service_PeopleService( $gClient );
  $gMe = $gPlusService->people->get( "people/me",['personFields' => 'birthdays']);
  $birthDaysStr = "";
  try {
    $birthDaysFound = $gMe->getBirthdays();
    if (is_array($birthDaysFound) && count($birthDaysFound) > 0 && is_object($birthDaysFound[0])) {
      $birthDaysFound = $birthDaysFound[0]->getDate();
      $birthDaysStr = sprintf("%d-%d-%d", $birthDaysFound->getYear(), $birthDaysFound->getMonth(), $birthDaysFound->getDay());
      $birthDaysStr = date("Y-m-d", strtotime($birthDaysStr));
    }
  } catch(Exception $e) {
      $birthDaysStr = "";
  }
  // Getting user profile info 
  $gpUserData = array(); 
  $gpUserData['oauth_uid']      = !empty($gpUserProfile['id'])?         $gpUserProfile['id']:''; 
  $gpUserData['first_name']     = !empty($gpUserProfile['given_name'])? $gpUserProfile['given_name']:''; 
  $gpUserData['last_name']      = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:''; 
  $gpUserData['full_name']      = !empty($gpUserProfile['name'])?       $gpUserProfile['name']:'';
  $gpUserData['email']          = !empty($gpUserProfile['email'])?      $gpUserProfile['email']:''; 
  $gpUserData['gender']         = !empty($gpUserProfile['gender'])?     $gpUserProfile['gender']:'unknown'; 
  $gpUserData['g_locale']       = !empty($gpUserProfile['locale'])?     $gpUserProfile['locale']:''; 
  $gpUserData['picture']        = !empty($gpUserProfile['picture'])?    $gpUserProfile['picture']:''; 
  $gpUserData['birthday']       = !empty($birthDaysStr)?    $birthDaysStr:''; 
  
  //All Information collected from Google Client - Store it in DB:
  //This also create the sessions:
  $User->save_g_signup_user($g_token, $gpUserData); 
  
  //Redirect to login session based.
  Page::jump_to_page("main");

} else {
  Page::error_page("g_login_no_code");
}