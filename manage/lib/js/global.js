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


/*****************************  MAIN APP CLASS *********************************************/
let sikbase = {
    init: function() {

    },
    apiRequest: function(url, req, _data, handlers) {
        //Required data:
        let data = {
            'request_token': $('meta[name=csrf-token]').attr('content'),
            'request_type': req,
        };
        //Build request:
        let ajaxSet = {
            type: 'POST',
            dataType: 'json',
            data: $.extend(data, _data),
            success: function() {},
            error: function(jqXhr, textStatus, errorMessage) {
                console.log("ERROR on AJAX", errorMessage);
                console.log("ERROR on AJAX", jqXhr);
                console.log("ERROR on AJAX", textStatus);
            },
            complete: function() {},
        };
        //Extend settings & handlers:
        $.extend(ajaxSet, handlers);
        //Execute:
        console.log(ajaxSet);
        return $.ajax(url, ajaxSet);
    },
    ajaxDataTable: function(url, req, params) {
        console.log(params);
        return sikbase.apiRequest(
            url,
            req,
            params.data, {
                error: params.error,
                success: function(res) {
                    params.success(res.data);
                }
            }
        );
    },
    serializeToObject: function(form, exclude) {
        exclude || (exclude = []);
        let obj = {},
            $form = $(form);
        if ($form.length) {
            $form
                .find("input, select, textarea") // Loop all input fields 
                .not(':input[type=button], :input[type=submit], :input[type=reset]') // We don't want those:
                .each(function(i, e) {
                    let _name = (e.name) ? e.name : e.id; //Make sure we have names otherwise use the ID:
                    if (_name.length && exclude.indexOf(_name) === -1) { //If not excluded:
                        obj[_name] = $(e).val() || "";
                    }
                });
        }
        return obj;
    },
    /*******************  HELPERS ***************************** */
    redirectPost(to, args, method = "POST", absolute = false) {
        let form = '';
        let path = absolute ? (baseUrl + to) : to;
        $.each(args, function(key, value) {
            value = (typeof value === 'string') ? value.split('"').join('\"') : value;
            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
        });
        $('<form action="' + path + '" method="' + method + '">' + form + '</form>').appendTo($(document.body)).submit();
    },
    redirectPage(to, click = false, delay = 0, absolute = false) {
        setTimeout(function() {
            let path = absolute ? (baseUrl + to) : to;
            if (click) window.location.href = path;
            else window.location.replace(path);
        }, delay);
    },
    reloadPage(delay = 0) {
        this.redirectPage(window.location.href, false, delay, false);
    },
    openInNewTab(href) {
        Object.assign(document.createElement('a'), {
            target: '_blank',
            href: href,
        }).click();
    },
    updateUrl(data, title, url) {
        if (typeof window.history.replaceState === 'function') {
            window.history.replaceState(data, title, url);
        }
    },
    format(fmt, ...args) {
        return fmt
            .split("%%")
            .reduce((aggregate, chunk, i) =>
                aggregate + chunk + (typeof args[i] !== 'undefined' ? args[i] : ""), "");
    },
    getCharacterLength(str) {
        return [...str].length;
    },
    getKeyByValue(object, value) {
        return Object.keys(object).find(key => object[key] === value);
    },
    /*******************  GENERIC EVENTS ***************************** */
    scrollToAnimated(selector, speed = 800) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(selector).eq(0).offset().top
        }, speed);
    }
}