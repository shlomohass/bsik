/******************************  SIDE MENU HANDLERS  *****************************/

$(function() {
    //Expand menu:
    $(".admin-menu > .menu-entry.has-submenu").on("click", function() {
        $(this).toggleClass("open-menu");
    });
    //Load module by menu click:
    $(".admin-menu .menu-entry").not(".has-submenu").on("click", function(e) {
        e.stopPropagation();
        let load = $(this).data("menuact");
        console.log("load module: ", load);
    });
});