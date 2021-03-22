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

})(jQuery, this, document, sikbase);