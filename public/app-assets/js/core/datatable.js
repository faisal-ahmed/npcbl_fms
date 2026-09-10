/*=========================================================================================
  File Name: datatable.js
  Description: Template related datatable JS.
==========================================================================================*/

(function(window, document, $) {
    //new DataTable('#data_table');
    $('#data_table').DataTable( {
        "scrollY": 500,
        "scrollX": true,
        "responsive": false

    } );
})(window, document, jQuery);