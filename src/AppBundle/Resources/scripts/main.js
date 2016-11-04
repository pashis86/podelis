$(document).ready(function() {

    $("#formCategories").hide();

    $( "#formButton" ).click(function() {
        $( "#formCategories" ).toggle( "slow", function() {
            // Animation complete.
        });
    });
});