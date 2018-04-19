/**
 * Created by zeeshan on 9/22/2014.
 */
//CODE FO A COUNTDOWN TIMER////////////////////////////////////
function display_c(start){ //JUST CALL THIS FUNCTION AND PASS THE NUMBER OF SECONDS YOU WANT TO COUNDOWN.
    window.start = parseFloat(start);
    var end = 0 // change this to stop the counter at a higher value
    var refresh=1000; // Refresh rate in milli seconds
    if(window.start >= end ){
        mytime=setTimeout('display_ct()',refresh)
    }
    else {
        //alert("Time Over ");//code here what you want to do after the time completes.
        var timeOver = true;
    }
}

function display_ct() {
// Calculate the number of days left
    var days=Math.floor(window.start / 86400);
// After deducting the days calculate the number of hours left
    var hours = Math.floor((window.start - (days * 86400 ))/3600)
// After days and hours , how many minutes are left
    var minutes = Math.floor((window.start - (days * 86400 ) - (hours *3600 ))/60)
// Finally how many seconds left after removing days, hours and minutes.
    var secs = Math.floor((window.start - (days * 86400 ) - (hours *3600 ) - (minutes*60)))

    var x = window.start;


    document.getElementById('ct').innerHTML = x;    //IN place of 'ct' you can enter the target div id where you want to display.
    window.start= window.start- 1;

    tt=display_c(window.start);
}
//---------------------------------------------//-----------------------------------------------//