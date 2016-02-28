var updateRange = function (rate) {
    if (rate.id == 'rate') {
        $('#rate-value').val(rate.value);
    }
    if (rate.id == 'rate-value') {
        $('#rate').val(rate.value);
    }
}

var colorPattern = [
    "#000000", "#FFFF00", "#1CE6FF", "#FF34FF", "#FF4A46", "#008941", "#006FA6", "#A30059",
    "#7A4900", "#0000A6", "#63FFAC", "#B79762", "#004D43", "#8FB0FF", "#997D87",
    "#5A0007", "#809693", "#1B4400", "#4FC601", "#3B5DFF", "#4A3B53", "#FF2F80",
    "#61615A", "#BA0900", "#6B7900", "#00C2A0", "#FFAA92", "#FF90C9", "#B903AA", "#D16100",
    "#DDEFFF", "#000035", "#7B4F4B", "#A1C299", "#300018", "#0AA6D8", "#013349", "#00846F",
    "#372101", "#FFB500", "#C2FFED", "#A079BF", "#CC0744", "#C0B9B2", "#C2FF99", "#001E09",
    "#00489C", "#6F0062", "#0CBD66", "#EEC3FF", "#456D75", "#B77B68", "#7A87A1", "#788D66",
    "#885578", "#FAD09F", "#FF8A9A", "#D157A0", "#BEC459", "#456648", "#0086ED", "#886F4C",

    "#34362D", "#B4A8BD", "#00A6AA", "#452C2C", "#636375", "#A3C8C9", "#FF913F", "#938A81",
    "#575329", "#00FECF", "#B05B6F", "#8CD0FF", "#3B9700", "#04F757", "#C8A1A1", "#1E6E00",
    "#7900D7", "#A77500", "#6367A9", "#A05837", "#6B002C", "#772600", "#D790FF", "#9B9700",
    "#549E79", "#FFF69F", "#201625", "#72418F", "#BC23FF", "#99ADC0", "#3A2465", "#922329",
    "#5B4534", "#FDE8DC", "#404E55", "#0089A3", "#CB7E98", "#A4E804", "#324E72", "#6A3A4C",
    "#83AB58", "#001C1E", "#D1F7CE", "#004B28", "#C8D0F6", "#A3A489", "#806C66", "#222800",
    "#BF5650", "#E83000", "#66796D", "#DA007C", "#FF1A59", "#8ADBB4", "#1E0200", "#5B4E51",
    "#C895C5", "#320033", "#FF6832", "#66E1D3", "#CFCDAC", "#D0AC94", "#7ED379", "#012C58"
];
$(document).ready(function () {
    var lesson_id = $("#lesson_id").val();
    var _token = $('input[name="_token"]').val();

    if ($('#ignore-ratings').length) {
        $('#ignore-ratings').multiselect();
    }

    if ($('#ignore-minutes').length) {
        $('#ignore-minutes').multiselect();
    }

    $("#rate").rating();

    $('#rate').on('rating.change', function (event, value, caption) {

        $.ajax({
                method: "POST",
                url: "/rating",
                data: {lesson_id: lesson_id, rating: value},
                headers: {
                    'X-CSRF-Token': _token
                },
                beforeSend: function (xhr) {
                    $('#rate').rating('refresh', {disabled: true});
                    $('.rating-container .caption').append('<span class="label" id="send-status"></span>');
                    $('#send-status').removeClass().addClass('label label-info').html('sending...');
                }
            })
            .done(function (data) {
                $('#rate').rating('refresh', {disabled: false});
                $('.rating-container .caption').append('<span class="label" id="send-status"></span>');
                $('#send-status').removeClass().addClass('label label-success').html('sent');
            })
            .fail(function () {
                $('#rate').rating('refresh', {disabled: false});
                $('.rating-container .caption').append('<span class="label" id="send-status"></span>');
                $('#send-status').removeClass().addClass('label label-warning').html('error');
                alert("error: cannot submit the rating");
            });
    });

    $('#submit-bookmark').click(function () {
        $.ajax({
                method: "POST",
                url: "/bookmark",
                data: {lesson_id: lesson_id, bookmark: $('#bookmark').val()},
                headers: {
                    'X-CSRF-Token': _token
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
                headers: {
                    'X-CSRF-Token': _token
                },
                url: "/rating/" + lesson_id
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

    if ($('#rate').length) {
        setInterval(ajax_call, interval);
    }

    var chart = c3.generate({
        bindto: '#chart',
        data: {
            json: {}
        },
        axis: {
            y: {
                max: 5,
                min: 1
            },
            x: {
                min: -1,
                tick: {
                    culling: {
                        max: 1 // the number of tick texts will be adjusted to less than this value
                    }
                    // for normal axis, default on
                    // for category axis, default off
                }
            }
        }
    });
    $.ajax({
            method: "GET",
            headers: {
                'X-CSRF-Token': _token
            },
            url: "/rating/" + lesson_id
        })
        .done(function (data) {
            data = jQuery.parseJSON(data);
            chart.load({
                json: data.ratings
            });
            chart.xgrids(data.bookmarks);
        });


    if ($('#chart-individual').length) {
        var chart2 = c3.generate({
            bindto: '#chart-individual',
            data: {
                json: {}
            },
            color: {pattern: colorPattern},
            axis: {
                y: {
                    max: 5,
                    min: 1
                },
                x: {
                    min: -1,
                    tick: {
                        culling: {
                            max: 1 // the number of tick texts will be adjusted to less than this value
                        }
                        // for normal axis, default on
                        // for category axis, default off
                    }
                }
            }
        });
        $.ajax({
                method: "GET",
                headers: {
                    'X-CSRF-Token': _token
                },
                url: "/rating/" + lesson_id + "/users"
            })
            .done(function (data) {
                data = jQuery.parseJSON(data);
                chart2.load(data);
            });
    }

    $('.enable-rating').click(function () {
        var _this = this;
        var rating_id = $(_this).data('id');
        $.ajax({
                method: "POST",
                url: "/rating/" + rating_id + "/toggleDelete",
                headers: {
                    'X-CSRF-Token': _token
                }
            })
            .done(function (data) {
                if ($(_this).data('enabled')) {
                    $(_this).removeClass('btn-default').addClass('btn-primary').html('&nbsp;Enable');

                    $(_this).data('enabled', 0);
                } else {
                    $(_this).removeClass('btn-primary').addClass('btn-default').html('Disable');

                    $(_this).data('enabled', 1);
                }
            })
            .fail(function () {
                alert("error: cannot enable/disable the rating");
            });
    });

    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.ajaxOptions = {type: "PUT"};
    $('.bookmarked_at').editable({
        ajaxOptions: {
            type: 'put',
            dataType: 'json',
            headers: {

                'X-CSRF-Token': _token
            },
        },
        params: function (params) {
            //originally params contain pk, name and value
            params.id = params.pk;
            params.bookmarked_at = params.value;
            return params;
        }
    });
    $('.bookmark').editable({
        ajaxOptions: {

            type: 'put',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': _token
            },
        },
        params: function (params) {
            //originally params contain pk, name and value
            params.id = params.pk;
            params.bookmark = params.value;
            return params;
        }
    });

    $('#save-new-bookmark').click(function () {
        $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-Token': _token
                },
                url: "/bookmark",
                data: {
                    lesson_id: lesson_id,
                    bookmark: $('#new_bookmark').val(),
                    bookmarked_at: $('#new_bookmarked_at').val()
                },
                headers: {
                    'X-CSRF-Token': _token
                }
            })
            .done(function (data) {
                location.reload();
            })
            .fail(function () {
                alert("error: cannot submit the bookmark");
            });
        location.reload();
    });

    var chartUserSummary = c3.generate({
        bindto: '#chart-user-summary',
        data: {
            json: {}
        },
        axis: {
            x: {
                type: 'category',
                categories: ['Number of ratings per user']
            }
        },
        color: {pattern: colorPattern},
    });
    var chartRatingRange = c3.generate({
        bindto: '#chart-rating-range',
        data: {
            json: {}
        },
        axis: {
            x: {
                type: 'category',
                categories: ['Range of ratings per user']
            }
        },
        color: {pattern: colorPattern},
    });
    var chartTotalRatings = c3.generate({
        bindto: '#chart-total-ratings',
        data: {
            json: {}
        },
        axis: {
            x: {
                type: 'category',
                categories: ['Number of ratings']
            }
        }
    });
    $.ajax({
            method: "GET",
            headers: {
                'X-CSRF-Token': _token
            },
            url: "/rating/" + lesson_id + "/rating-summary"
        })
        .done(function (data) {
            data = jQuery.parseJSON(data);
            chartUserSummary.load(data.user);
            chartRatingRange.load(data.range);
            chartTotalRatings.load(data.total);
        });

    $('#update-rating-chart').click(function () {
        $.ajax({
                method: "POST",
                data: {ignoreRatings: $('#ignore-ratings').val(), ignoreMinutes: $('#ignore-minutes').val()},
                headers: {
                    'X-CSRF-Token': _token
                },
                url: "/rating/" + lesson_id
            })
            .done(function (data) {
                data = jQuery.parseJSON(data);
                chart.load({
                    json: data.ratings
                });
                //chart.xgrids(data.bookmarks);
            });
    });
});