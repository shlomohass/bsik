/******************************  SIDE MENU HANDLERS  *****************************/
import * as $$ from './utils.module.js';
import * as SikCore from './core.module.js';
import { SikNotify } from './sikNotify.module.js';
import * as SikDataTables from './sikDataTables.module.js';



/*****************************  MAIN APP CLASS *********************************************/
window["Bsik"] = {};
let Bsik = window.Bsik;
window.Bsik["notify"] = SikNotify.init();
window.Bsik["core"] = SikCore;
window.Bsik["dataTables"] = SikDataTables;

/*****************************  BASIC CORE EVENTS *********************************************/

//Default handlers:
document.addEventListener("DOMContentLoaded", function(event) {

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