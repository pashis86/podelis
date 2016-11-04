$(document).ready(function() {

    $("#formCategories").hide();

    $( "#formButton" ).click(function() {
        $( "#formCategories" ).toggle( "slow", function() {
            // Animation complete.
        });
    });
});


var sec = 0;
function pad ( val ) { return val > 9 ? val : "0" + val; }
setInterval( function(){
    $("#seconds").html(pad(++sec%60));
    $("#minutes").html(pad(parseInt(sec/60,10)));
    $("#hours").html(pad(parseInt(sec/3600,10)))
}, 1000);