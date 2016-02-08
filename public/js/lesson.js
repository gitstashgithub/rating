var updateRange = function (rate) {
    if (rate.id == 'rate') {
        $('#rate-value').val(rate.value);
    }
    if (rate.id == 'rate-value') {
        $('#rate').val(rate.value);
    }
}
$(document).ready(function () {
    $("#rate").rating();
    $('.star-rating').append('<div class="caption"><span class="label" id="send-status"></span></div>');
    // $('#submit-rate').click(function () {
    //     $.ajax({
    //             method: "POST",
    //             url: "/rating",
    //             data: {lesson_id: $('#lesson_id').val(), rating: $('#rate').val()},
    //             headers: {
    //                 'X-CSRF-Token': $('input[name="_token"]').val()
    //             },
    //             beforeSend: function (xhr) {
    //                 $('#submit-rate').attr('disabled', 'disabled');
    //             }
    //         })
    //         .done(function (data) {
    //             $('#submit-rate').removeAttr('disabled');
    //         })
    //         .fail(function () {
    //             $('#submit-rate').removeAttr('disabled');
    //             alert("error: cannot submit the rating");
    //         });
    // });

    $('#rate').on('rating.change', function(event, value, caption) {
        $.ajax({
                method: "POST",
                url: "/rating",
                data: {lesson_id: $('#lesson_id').val(), rating: value},
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                beforeSend: function (xhr) {
                    $('#send-status').removeClass().addClass('label label-info').html('sending...');
                }
            })
            .done(function (data) {
                $('#send-status').removeClass().addClass('label label-success').html('sent');
            })
            .fail(function () {
                $('#send-status').removeClass().addClass('label label-warning').html('error');
                alert("error: cannot submit the rating");
            });
    });

    $('#submit-bookmark').click(function(){
        $.ajax({
                method: "POST",
                url: "/bookmark",
                data: {lesson_id: $('#lesson_id').val(), bookmark: $('#bookmark').val()},
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                beforeSend: function (xhr) {
                    $('#submit-bookmark').html('Saving...');
                }
            })
            .done(function (data) {
                $('#submit-bookmark').html('Save');
                $('#bookmark').val('');
            })
            .fail(function () {
                $('#submit-bookmark').html('Save');
                alert("error: cannot submit the bookmark");
            });
    });



    var ajax_call = function () {
        $.ajax({
                method: "GET",
                url: "/rating/" + $("#lesson_id").val()
            })
            .done(function (data) {
                data = jQuery.parseJSON(data);
                chart.load({
                    json: data.ratings
                });
                chart.xgrids(data.bookmarks);
            });
    };

    var interval = 1000 * 60 * 0.1; // every 1 minute

    if($('#rate').length){
        setInterval(ajax_call, interval);
    }

    $.ajax({
            method: "GET",
            url: "/rating/" + $("#lesson_id").val()
        })
        .done(function (data) {
            data = jQuery.parseJSON(data);
            chart.load({
                json: data.ratings
            });
            chart.xgrids(data.bookmarks);
        });

    var chart = c3.generate({
        bindto: '#chart',
        data: {
            json: {}
        },
        axis: {
            y: {
                max: 5,
                min: 1
            }
        }
    });
});