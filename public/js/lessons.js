$(document).ready(function () {
    $('.enable-lesson').click(function () {
        var _this = this;
        var lesson_id = $(_this).data('id');
        $.ajax({
                method: "POST",
                url: "/lesson/"+lesson_id+"/enable",
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                }
            })
            .done(function (data) {
                if($(_this).data('enabled')){
                    $(_this).removeClass('btn-primary').addClass('btn-default').html('&nbsp;Enable');
                    $('#enabled-label-'+lesson_id).html('');

                    $(_this).data('enabled',0);
                }else{
                    $('.enable-lesson').each(function () {
                        $(this).removeClass('btn-primary').addClass('btn-default').html('&nbsp;Enable');
                    });
                    $(_this).removeClass('btn-default').addClass('btn-primary').html('Disable');

                    $('.enabled-label').html('');
                    $('#enabled-label-'+lesson_id).html('<span class="label label-success">Enabled</span>');

                    $(_this).data('enabled',1);
                }
            })
            .fail(function () {
                alert("error: cannot enable/disable the lesson");
            });
    });
});