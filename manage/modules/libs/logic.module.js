/******************************************************************************/
// Created by: SIKTEC.
// Release Version : 1.0.0
// Creation Date: 2021-03-16
// Copyright 2021, SIKTEC.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.0:
    ->initial 
*******************************************************************************/
(function($, window, document, Bsik, undefined) {


    /************ Initiate the base js object: ******************/
    Bsik.core.init();

    /************* Add dynamic table operation handler **********/
    Bsik.core.tableOperateEvents = {
        'click .like': function(e, value, row, index) {
            console.log('You click like action, row: ' + JSON.stringify(row));
        },
        'click .delete': function(e, value, row, index) {
            console.log("delete row called!", this);
            //this.$el.boo
            //Call API to remove:
            //Removes the row from Table UI
            this.$el.bootstrapTable('remove', {
                field: 'id',
                values: [row.id]
            })
        }
    };
    console.log("module script");

    /************* Search libs api *******************************/
    $(function() {
        $("#search-npm").on("click", function() {
            let data = {
                "search": $("#search-term").val()
            };
            Bsik.core.apiRequest(
                false, // FALSE = auto add url
                "search_frontend_libs",
                data, {
                    success: function(res) {
                        console.log(res);
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        console.log(jqXhr);
                        if ('responseJSON' in jqXhr && 'code' in jqXhr.responseJSON && 'message' in jqXhr.responseJSON) {
                            switch (jqXhr.responseJSON.code) {
                                case 400:
                                    {
                                        Bsik.notify.message("warn", "Please enter a search term.", true);
                                    }
                                    break;
                                default:
                                    {
                                        Bsik.notify.message("error", "API Error occurred - Refresh the page and try again.", true);
                                    }
                            }
                        } else {
                            Bsik.notify.message("error", "API Internal Server Error occurred - Refresh the page and try again.", true);
                        }
                    }
                }
            );
        });
    });



})(jQuery, this, document, window.Bsik);