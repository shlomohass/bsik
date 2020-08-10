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
if (!isset($Page)) { include_once(PATH_REQUIRED_PAGES."error.php?p=main&code=10"); die(); }

?>
<!doctype html>
<html lang="<?php print $Page->meta("lang"); ?>">
	<head>
		<meta charset="<?php print $Page->meta("charset"); ?>">
		<meta name="viewport" content="<?php print $Page->meta("viewport"); ?>">
		<meta name="author" content="<?php print $Page->meta("author"); ?>">
        <meta name="description" content="<?php print $Page->meta("description"); ?>">
        <?php print $Page->token["meta"]; ?>
        <?php
            foreach ($Page->op_meta() as $name => $meta)
                print "<meta name='".$name."' content='".$meta."' />";
        ?>
        <title><?php print $Page->meta("title"); ?></title>
        <link rel="icon" href="<?php print $Page->meta("icon"); ?>">
        <!-- START : Head includes -->
<?php
/******************************  Load head libs  *****************************/
foreach ($Page->includes["head"]["css"] as $include) {
    print "<link rel='stylesheet' href='".$include["link"]."' ".$include["add"]." />";
}
foreach ($Page->includes["head"]["js"] as $include) {
    print "<script type='text/javascript' src='".$include["link"]."' ".$include["add"]."></script>";
}
?>
        <!-- END : Head includes -->
        <!-- START : Document Direction -->
        <style>
            * { direction: <?php print $Page->meta("direction"); ?>;}
        </style>
        <!-- END : Document Direction -->
</head>
<body <?php print $Page->body_tag(); ?>>
<!-- START : Body-Begin includes -->
<?php
/******************************  Load head includes  *****************************/
foreach ($Page->includes["body-begin"]["css"] as $lib) {
    print "<link rel='stylesheet' href='".$include["link"]."' ".$include["add"]." />";
}
foreach ($Page->includes["body-begin"]["js"] as $include) {
    print "<script type='text/javascript' src='".$include["link"]."' ".$include["add"]." ></script>";
}
?>
<!-- END : Body-Begin includes -->