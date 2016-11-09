$(document).ready(function() {

    function scrollWin() {
        //window.scrollTo(0, $(".formCategories").offset().top);
        $('html, body').animate({
                scrollTop: $('.formCategories').position().top },
            1000
        );

    }

    $(".formCategories").hide();

    $(".registerForm").hide();

    $(".loginForm").hide();

    $( ".formButton" ).click(function() {

        $( ".formCategories" ).toggle( "slow", function() {
            scrollWin();
            // Animation complete.
        });

    });

    $( ".loginButton" ).click(function() {

        $(".registerForm").hide();

        $( ".loginForm" ).toggle( "slow", function() {
            // Animation complete.
        });
    });

    $( ".registerButton" ).click(function() {

        $(".loginForm").hide();

        $( ".registerForm" ).toggle( "slow", function() {
            // Animation complete.
        });
    });
});

$('.myIframe').css('height', $('.myIframe').width()*9/16+'px');

var sec = 0;
function pad ( val ) { return val > 9 ? val : "0" + val; }
setInterval( function(){
    $("#seconds").html(pad(++sec%60));
    $("#minutes").html(pad(parseInt(sec/60,10)));
    $("#hours").html(pad(parseInt(sec/3600,10)))
}, 1000);/**
 * Created by pc on 16.11.6.
 */