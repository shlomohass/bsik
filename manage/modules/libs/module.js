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
(function($, window, document, sikbase, undefined) {


    /************ Initiate the base js object: ******************/
    sikbase.init();

    /************* Add dynamic table operation handler **********/
    sikbase.tableOperateEvents = {
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
            sikbase.apiRequest(
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
                                        alert("Search is empty");
                                    }
                                    break;
                                default:
                                    {
                                        alert("API Error occurred - Refresh the page and try again.")
                                    }
                            }
                        } else {
                            alert("API Internal Server Error occurred - Refresh the page and try again.")
                        }
                    }
                }
            );
        });
    });



})(jQuery, this, document, sikbase);