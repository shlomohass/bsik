<?php
/******************************  intellisense  *****************************/
if (!isset($APage)) {
    $APage = new APage();
} 

$APage->include("head", "css", "path", ["name" => "lib/required/bootstrap/css/bootstrap.css"]);
$APage->include("head", "css", "path", ["name" => "lib/css/global.css"]);
$APage->include("head", "css", "path", ["name" => "lib/css/login.css"]);
$APage->include("head", "js",  "path", ["name" => "lib/required/jquery/jquery.min.js"]);

/******************************  Guard  *****************************/

?>
<html data-docby="BSIK Platfom">
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="">
        <meta name="author" content="SIKTEC">
        <meta name="description" content="PHP made simple the correct way to build fast and powerful PHP web apps">
        <meta http-equiv="X-UA-Compatible" content="IE=7">
        <?php print $APage->token["meta"]; ?>
        <title>SIK Framework - Login Page</title>
        <link rel="icon" href="">
        <!-- START : Head includes -->
        <?PHP
            $APage->render_libs("css", "head");
            $APage->render_libs("js", "head");
        ?>
        <!-- END : Head includes -->
    </head>
    <body style="">        
        <!-- END : body includes -->
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-2"></div>
                <div class="col-lg-6 col-md-8 login-box">
                    <div class="col-lg-12 login-key">
                        <i class="fa fa-key" aria-hidden="true"></i>
                    </div>
                    <div class="col-lg-12 login-title">
                        ADMIN PANEL
                    </div>

                    <div class="col-lg-12 login-form">
                        <div class="col-lg-12 login-form">
                            <form>
                                <div class="form-group">
                                    <label class="form-control-label">USERNAME</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">PASSWORD</label>
                                    <input type="password" class="form-control" i>
                                </div>

                                <div class="col-lg-12 loginbttm">
                                    <div class="col-lg-6 login-btm login-text">
                                        <!-- Error Message -->
                                    </div>
                                    <div class="col-lg-6 login-btm login-button">
                                        <button type="submit" class="btn btn-outline-primary">LOGIN</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2"></div>
                </div>
            </div>
        </div>
    </body>
</html>



