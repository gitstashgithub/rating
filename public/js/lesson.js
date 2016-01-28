var updateRange = function (rate) {
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
        }
    });
});