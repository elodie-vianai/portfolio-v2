/**
 * Created by Elodie Vianai on 30/05/2017.
 */
$(document).ready(function () {

    $('#goBack').on('click', function (e) {
        window.history.go(-1);
    });

    $('#type').on('load', function(e) {
        console.log('hello');
    });

});