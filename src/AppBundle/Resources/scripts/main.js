function timedCounter(finalValue, seconds, callback){

    var startTime = (new Date).getTime();
    var milliseconds = seconds*1000;

    (function update(){

        var currentTime = (new Date).getTime();
        var value = finalValue*(currentTime - startTime)/milliseconds;

        if(value >= finalValue)
            value = finalValue;
        else
            setTimeout(update, 250);

        callback && callback(value);

    })();

}

timedCounter(75, 5, function(value){
    value = Math.floor(value);
    $('.user-count').html(value);
});

function sendAnswer(inputSelector, url, question) {
    $(inputSelector).on('change', function () {
        var answer = $(inputSelector.checked).val();
        $.ajax({
            type: "POST",
            url: url,
            data: { 'question': question, 'answer': answer }
        });
    });
}

function sendAnswers(inputSelector, url, question) {
    $(inputSelector).on('change', function () {
        var answers = [];
        var answer = $(inputSelector.checked).each(function () {
            answers.push($(this).val());
        });
        $.ajax({
            type: "POST",
            url: url,
            data: { 'question': question, 'answer': answers }
        });
    });
}