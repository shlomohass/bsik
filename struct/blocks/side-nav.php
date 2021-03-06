<?php
/******************************************************************************/
// Created by: Shlomo Hassid.
// Release Version : 1.0.1
// Creation Date: 17/05/20202
// Copyright 2020, Shlomo Hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->initial, Creation
*******************************************************************************/
/******************************  Guard  *****************************/
if (!isset($Page)) { include_once(PATH_REQUIRED_PAGES."error.php?p=main&code=10"); die(); }


/******************************  Load Some Data  *****************************/
//Load side nav navigation links:
$Page->store("side nav links", $Data->get(
    "construct_side_nav",
    false,
    "`side_nav_pos` ASC"
));
Trace::add_trace("Load side nav links from DB", __FILE__, $Page->get("side nav links"));

/******************************  Prepare data   *****************************/
//Handle user login status:
if ($User->userIsSigned) {
    //User is logged in:
    $Page->store("user-link-class", "logged-in");
} else {
    //User is not logged in:
    $Page->store("user-link-class", "not-logged-in");
}

/******************************  Register for debugger  *****************************/
Trace::reg_var("Page Storage", $Page->get(true));
?>
<!-- START : Side Navigation Bar -->
<aside class="side-nav">
    <ul class="side-nav-icons rem-list">
        <li>
            <a href="#" class="open-modal <?php print $Page->get("user-link-class"); ?>" data-modalname="login-menu"><i class="fas fa-user-circle"></i></a>
        </li>
        <li><a href="#"><i class="fas fa-home"></i></a></li>
        <li><a href="#"><i class="fas fa-arrows-alt-h"></i></a></li>
    </ul>
    <ul class="side-nav-links rem-list">
        <?php 
            //Print side nav links:
            foreach($Page->get("side nav links") as $key => $side_link) { 
                print "<li data-pos='".$side_link["side_nav_pos"]."'>".
                            "<a href='".$side_link["side_nav_link"]."'>".
                                "<i class='fas ".$side_link["side_nav_icon"]."'></i>".
                                    $side_link["side_nav_text"].
                            "</a>".
                      "</li>";
            }
        ?>
    </ul>
</aside>
<!-- END : Side Navigation Bar -->
<!-- START : Used Modal for side Nav -->
    <!-- Modal Log in  -->
    <!-- TODO : generate modal codes by custom Page Modal builder -->
    <div class="modal login-menu" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>התחברות נדרשת</h2>
                <a href="#" class="sign-close close-modal" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body signin-form">
                <button type="button" class="google-button" onclick="location.href = '<?php print $User->gSignUrl; ?>';">
                    <span class="google-button__icon">
                        <svg viewBox="0 0 366 372" xmlns="http://www.w3.org/2000/svg"><path d="M125.9 10.2c40.2-13.9 85.3-13.6 125.3 1.1 22.2 8.2 42.5 21 59.9 37.1-5.8 6.3-12.1 12.2-18.1 18.3l-34.2 34.2c-11.3-10.8-25.1-19-40.1-23.6-17.6-5.3-36.6-6.1-54.6-2.2-21 4.5-40.5 15.5-55.6 30.9-12.2 12.3-21.4 27.5-27 43.9-20.3-15.8-40.6-31.5-61-47.3 21.5-43 60.1-76.9 105.4-92.4z" id="Shape" fill="#EA4335"/><path d="M20.6 102.4c20.3 15.8 40.6 31.5 61 47.3-8 23.3-8 49.2 0 72.4-20.3 15.8-40.6 31.6-60.9 47.3C1.9 232.7-3.8 189.6 4.4 149.2c3.3-16.2 8.7-32 16.2-46.8z" id="Shape" fill="#FBBC05"/><path d="M361.7 151.1c5.8 32.7 4.5 66.8-4.7 98.8-8.5 29.3-24.6 56.5-47.1 77.2l-59.1-45.9c19.5-13.1 33.3-34.3 37.2-57.5H186.6c.1-24.2.1-48.4.1-72.6h175z" id="Shape" fill="#4285F4"/><path d="M81.4 222.2c7.8 22.9 22.8 43.2 42.6 57.1 12.4 8.7 26.6 14.9 41.4 17.9 14.6 3 29.7 2.6 44.4.1 14.6-2.6 28.7-7.9 41-16.2l59.1 45.9c-21.3 19.7-48 33.1-76.2 39.6-31.2 7.1-64.2 7.3-95.2-1-24.6-6.5-47.7-18.2-67.6-34.1-20.9-16.6-38.3-38-50.4-62 20.3-15.7 40.6-31.5 60.9-47.3z" fill="#34A853"/></svg>
                    </span>
                    <span class="google-button__text">התחבר עם חשבון גוגל</span>
                </button>
                <fieldset>
                    <label for="username" class="required-field">שם משתמש:</label>
                    <input type="text" name="username" placeholder="example@mail.com" size="20" style="direction:ltr" class="" />
                    <label for="username" class="required-field">סיסמא:</label>
                    <input type="password" name="password" placeholder="&centerdot;&centerdot;&centerdot;&centerdot;&centerdot;&centerdot;&centerdot;&centerdot;" size="20" style="direction:ltr" />
                </fieldset>
                <button id="btn-signin" class="button button-outline">התחבר</button>
                <p class="text-medium-bold text-underline rem-margin"><span id="signup" class="add-pointer">הרשם בפעם הראשונה</span></p>
            </div>
            <div class="modal-body signup-form display-none">
                <fieldset>
                    <label for="email" class="required-field">הקלד כתובת מייל:</label>
                    <input type="text" name="email" placeholder="example@mail.com" size="55" style="direction:ltr" />
                    <label for="pass1" class="required-field">הקלד/י סיסמא:</label>
                    <input type="password" name="password" placeholder="לפחות 6 תווים" size="20" style="direction:ltr" />
                    <label for="passwordVerify" class="required-field">הקלד/י סיסמא בשנית:</label>
                    <input type="password" name="passwordVerify" placeholder="אימות סיסמא" size="20" style="direction:ltr" />
                    <label for="firstname" class="required-field">שם פרטי:</label>
                    <input type="text" name="firstname" placeholder="אותיות בלבד" size="20" style="direction:rtl" />
                    <label for="lastname" class="required-field">שם משפחה:</label>
                    <input type="text" name="lastname" placeholder="אותיות בלבד" size="20" style="direction:rtl" />
                    <label for="gender">בחר/י מין:</label>
                    <select name="gender">
                        <option value="male">זכר</option>
                        <option value="female">נקבה</option>
                        <option value="unknkown" selected>אחר</option>
                    </select>
                    <label for="organization">שם הארגון / חברה:</label>
                    <input type="text" name="organization" placeholder="לא חובה" size="35" style="direction:rtl" />
                </fieldset>
                <button id="btn-signup" class="button button-outline">הרשם</button>
                <p class="text-medium-bold text-underline rem-margin"><span id="signin" class="add-pointer">יש לך כבר משתמש?</span></p>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
    <!-- /Modal -->
<!-- END : Used Modal for side Nav -->