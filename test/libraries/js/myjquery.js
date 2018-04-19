/**
 * Created by noman on 2/24/14.
 */
$(function(){
    //activating the active links
    $("#expenses a:contains('Expenses')").parent().addClass('active');
    $("#stocks a:contains('Stocks')").parent().addClass('active');
    $("#sales a:contains('Sales')").parent().addClass('active');
    $("#dailyReports a:contains('Daily Reports')").parent().addClass('active');


  /*  $('ul.nav li.dropdown').hover(function(){
        $('.dropdown-menu', this).fadeIn();
    }, function(){
        $('.dropdown-menu', this).fadeOut('fast');
    });*/

    $('.tab-pane').find('table').find('tr').first().css('borderTop','2px solid white');

});
$(function(){
    //alert();
    //$('.datepicker').datepicker();

});

$( document ).ready(function() {
    $('.datepicker').datepicker();
});