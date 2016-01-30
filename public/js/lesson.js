var updateRange = function (rate) {
<<<<<<< HEAD
    if (rate.id == 'rate') {
        $('#rate-value').val(rate.value);
    }
    if (rate.id == 'rate-value') {
        $('#rate').val(rate.value);
    }
}
$(document).ready(function () {

    $('#submit-rate').click(function () {
        $.ajax({
                method: "POST",
                url: "http://rating.app/rate",
                data: {lesson_id: $('#lesson_id').val(), rate: $('#rate').val()},
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                beforeSend: function (xhr) {
                    $('#submit-rate').attr('disabled', 'disabled');
                }
            })
            .done(function (data) {
                $('#submit-rate').removeAttr('disabled');
            })
            .fail(function () {
                $('#submit-rate').removeAttr('disabled');
                alert("error: cannot submit the rating");
            });
    });

    var ajax_call = function () {
        $.ajax({
                method: "GET",
                url: "http://rating.app/rate/1",
            })
            .done(function (data) {
                chart.load({
                    json: jQuery.parseJSON(data)
                });
            });
    };

    var interval = 1000 * 60 * 0.1; // every 1 minute

    setInterval(ajax_call, interval);

    $.ajax({
            method: "GET",
            url: "http://rating.app/rate/1",
        })
        .done(function (data) {
            chart.load({
                json: jQuery.parseJSON(data)
            });
        });

    var chart = c3.generate({
        bindto: '#chart',
        data: {
            json: {}
        },
        axis: {
            y: {
                max: 4,
                min: 0
            }
=======
    if(rate.id=='rate'){
        $('#rate-value').val(rate.value);
    }
    if(rate.id=='rate-value'){
        $('#rate').val(rate.value);
    }
}
$( document ).ready(function() {

    var ajax_call = function() {
        //your jQuery ajax code
    };

    var interval = 1000 * 60 * 1; // every 1 minute

    setInterval(ajax_call, interval);

    var chart = c3.generate({
        bindto: '#chart',
        data: {
            columns: [
                ['Mean Rate', 2.21, 2.47, 2.95, 3.69, 3.45, 4.21],
                ['Median Rate', 3, 4, 2, 3, 3, 4]
            ]
>>>>>>> 66fab914a8312dd5b42b607974cc38613e2fac6b
        }
    });
});