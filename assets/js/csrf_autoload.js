$(document).ready(function() {

    // dyamic $name => $value
    var $name = window.csrf_token;
    var data = {};
    data[$name] = window.csrf_hash;

    $.ajaxSetup({
        data: data,
        cache: false
    });
});