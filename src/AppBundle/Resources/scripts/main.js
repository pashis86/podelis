$(document).ready(function () {
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