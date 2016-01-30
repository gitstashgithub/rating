var updateRange = function (rate) {
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
                url: "/rate",
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
                url: "/rate/" + $("#lesson_id").val()
            })
            .done(function (data) {
                chart.load({
                    json: jQuery.parseJSON(data)
                });
            });
    };

    var interval = 1000 * 60 * 0.1; // every 1 minute

    if($('#rate').length){
        setInterval(ajax_call, interval);
    }

    $.ajax({
            method: "GET",
            url: "/rate/" + $("#lesson_id").val()
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
        }
    });
});