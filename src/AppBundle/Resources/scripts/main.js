function sendAnswers(inputSelector, url, question) {
    $(inputSelector).on('change', function () {
        var answers = [];
        $(inputSelector).each(function () {
            if ($(this).is(':checked')) {
                answers.push($(this).val());
            }
        });

        $.ajax({
            type: "POST",
            url: url,
            data: {'question': question, 'answer': answers}
        });
    });
}

(function (e) {
    e.fn.countdown = function (t, n) {
        function i() {
            eventDate = Date.parse(r.date) / 1e3;
            currentDate = Math.floor(e.now() / 1e3);
            if (eventDate <= currentDate) {
                n.call(this);
                clearInterval(interval)
            }
            seconds = eventDate - currentDate;
            days = Math.floor(seconds / 86400);
            seconds -= days * 60 * 60 * 24;
            hours = Math.floor(seconds / 3600);
            seconds -= hours * 60 * 60;
            minutes = Math.floor(seconds / 60);
            seconds -= minutes * 60;
            days == 1 ? thisEl.find(".timeRefDays").text("day") : thisEl.find(".timeRefDays").text("days");
            hours == 1 ? thisEl.find(".timeRefHours").text("hour") : thisEl.find(".timeRefHours").text("hours");
            minutes == 1 ? thisEl.find(".timeRefMinutes").text("minute") : thisEl.find(".timeRefMinutes").text("minutes");
            seconds == 1 ? thisEl.find(".timeRefSeconds").text("second") : thisEl.find(".timeRefSeconds").text("seconds");
            if (r["format"] == "on") {
                days = String(days).length >= 2 ? days : "0" + days;
                hours = String(hours).length >= 2 ? hours : "0" + hours;
                minutes = String(minutes).length >= 2 ? minutes : "0" + minutes;
                seconds = String(seconds).length >= 2 ? seconds : "0" + seconds
            }
            if (!isNaN(eventDate)) {
                thisEl.find(".days").text(days);
                thisEl.find(".hours").text(hours);
                thisEl.find(".minutes").text(minutes);
                thisEl.find(".seconds").text(seconds)
            } else {
                alert("Invalid date. Example: 30 Tuesday 2013 15:50:00");
                clearInterval(interval)
            }
        }
        var thisEl = e(this);
        var r = {
            date: null,
            format: null
        };
        t && e.extend(r, t);
        i();
        interval = setInterval(i, 1e3)
    }
})(jQuery);

function solveIt(reqPath, questionId, inputElement, explanationEl) {
    $.ajax({
        type: "POST",
        url: reqPath,
        data: {'question': questionId},
        success: function (data) {
            var object = JSON.parse(data);

            for (var i = 0; i < object.length; i++) {
                object[i] = object[i].id;
            }

            var explanation = object['explanation']
                .replace('\<pre>', '\<pre><code class=\'language-php\'><xmp>')
                .replace('\</pre>', '\</xmp></code></pre>');
            var answers = object['answers'];

            for (var i = 0; i < answers.length; i++) {
                answers[i] = answers[i].id;
            }

            inputElement.prop('disabled', true);
            inputElement.each(function () {
                for (var i = 0; i < answers.length; i++) {
                    if (answers[i] == $(this).attr('value')) {
                        $(this).parent().prop('class', 'alert alert-success');
                    }
                }
            });
            explanationEl.html(explanation);
        }
    });
}


function reportQuestion(form, url, questionId) {

    form.one('submit', function(e){

        e.preventDefault();
        var formSerialize = $(this).serialize() + "&questionId=" + questionId;

        $.ajax({
            type: "POST",
            url: url,
            data: formSerialize,
            success: function () {
                 $('#reportBtn')
                     .removeClass('btn-danger')
                     .addClass('btn-success')
                     .attr('value', 'Report submitted!')
                     .trigger("click")
                     .attr('disabled', true);
                $('#submitReport').attr('disabled', true);

            },
            error: function () {
                $('#reportBtn').attr('value', 'Something went wrong!').trigger("click");
            }
        })
    });

}

function questionFormCollection() {
    $('.btn-add').click(function(event) {
        var collectionHolder = $('#' + $(this).attr('data-target'));
        var prototype = collectionHolder.attr('data-prototype');
        var length = collectionHolder.children().length;
        var form = prototype.replace(/__name__/g, length);


        if (length <= 8) {
            collectionHolder.append('<div>' + form + '<a href="#" class="remove-answer btn btn-danger">x</a>' + '</div>');
            $(this).attr('disabled', false);
        } else if (length < 5) {
            $(this).attr('disabled', true);
        }
        return false;
    });

    var answers = $('#answers');
    var i = 0;

    answers.children().each(function () {
        i++;
        if (i > 7) {
            $(this).append('<a href="#" class="remove-answer btn btn-danger">x</a>');
        }
    });

    answers.on('click', '.remove-answer', function(e) {
        e.preventDefault();
        $('.btn-add').attr('disabled', false);
        $(this).parent().remove();
        return false;
    });
}

function deleteRecord(url, entity) {
    $('#removeModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var modal = $(this);

        modal.find('.modal-body').text('Question: ' + name);
        $('#delete').one('click', function () {

            // $('#delete').attr('disabled', true);
            $.ajax({
                type: "POST",
                url: url,
                data: 'repository=' + entity + '&id=' + id,
                success: function () {
                    button.trigger("click");
//                    button.parent().parent().hide();
                },
                error: function () {
                    button.trigger("click");
                }
            })
        });
    });
}

function leaderboard(path, element) {
    element.DataTable({
        bProcessing: true,
        bServerSide: true,
        bPaginate: true,
        bFilter: false,
        order: [[2, 'desc']],
        columnDefs: [
            { orderSequence: [ "desc", "asc"], targets: [ 1, 2, 3, 4, 5, 6 ] }
        ],
        ajax:  {
            url: path,
            method: 'post',
            datatype: 'json'
        },
        columns: [
            {
                data: 'avatar',
                className: 'data-row',
                orderable: false,
                render: function(data) {
                    return '<img class="img-circle leaderboard-thumb" src="'+data+'" />';
                }
            },
            {
                mData: 'name',
                className: 'data-row',
                render: function (data, type, full) {
                    return data + ' ' + full['surname'];
                }
            },
            {
                data: 'correct',
                className: 'data-row'
            },
            {
                data: 'incorrect',
                className: 'data-row'
            },
            {
                data: 'testsTaken',
                className: 'data-row'
            },
            {
                data: 'percentage',
                className: 'data-row'
            },
            {
                data: 'timeSpent',
                className: 'data-row'
            }
        ]
    });
}
