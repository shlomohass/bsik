<?php
/******************************  Gaurd  *****************************/
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
        $Page->error_page("g_login_internal_error");
    }
  // get profile info
  $gpUserProfile = $google_oauth->userinfo->get();
  // Getting user profile info 
  $gpUserData = array(); 
  $gpUserData['oauth_uid']      = !empty($gpUserProfile['id'])?         $gpUserProfile['id']:''; 
  $gpUserData['first_name']     = !empty($gpUserProfile['given_name'])? $gpUserProfile['given_name']:''; 
  $gpUserData['last_name']      = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:''; 
  $gpUserData['email']          = !empty($gpUserProfile['email'])?      $gpUserProfile['email']:''; 
  $gpUserData['gender']         = !empty($gpUserProfile['gender'])?     $gpUserProfile['gender']:''; 
  $gpUserData['locale']         = !empty($gpUserProfile['locale'])?     $gpUserProfile['locale']:''; 
  $gpUserData['picture']        = !empty($gpUserProfile['picture'])?    $gpUserProfile['picture']:''; 
  // now you can use this profile info to create account in your website and make user logged in.
  var_dump($gpUserProfile);

} else {
  $Page->error_page("g_login_no_code");
}