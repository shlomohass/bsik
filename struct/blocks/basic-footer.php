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
<!-- START : Basic-footer html
<footer class="footer">
    <section class="container">
        <p>Designed with ♥ by <a target="_blank" href="http://cjpatoilo.com" title="CJ Patoilo">CJ Patoilo</a>. Licensed under the <a target="_blank" href="https://github.com/milligram/milligram#license" title="MIT License">MIT License</a>.</p>
    </section>
</footer>
<!-- END : Basic-footer html -->
<?php
/******************************  Load Body End includes  *****************************/
print "<!-- START : Body-End includes -->";
foreach ($Page->includes["body-end"]["css"] as $include) {
    print "<link rel='stylesheet' href='".$include["link"]."' ".$include["add"]." />";
}
foreach ($Page->includes["body-end"]["js"] as $include) {
    print "<script type='text/javascript' src='".$include["link"]."' ".$include["add"]."></script>";
}
print "<!-- END : Body-End includes -->";
?>
</body>
</html>