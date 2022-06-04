$(document).ready(function () {
})

postAjax = function (url, data, success) {
    var dataPeramiter = '';
    token = $('#token').val();
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            newkey = key + '=' + data[key] + '&';
            dataPeramiter += newkey;
        }
    }
    dataPeramiter += '_token=' + token + '&_method=POST';
    $.ajax({
        type: "post",
        url: url,
        data: dataPeramiter,
        cache: false,
        success: function (data) {
            if (success) {
                success(data);
            }
            return data;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText)
        }
    });
};


deleteAjax = function (url, data, success) {
    var dataPeramiter = '';
    token = $('#token').val();
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            newkey = key + '=' + data[key] + '&';
            dataPeramiter += newkey;
        }
    }
    dataPeramiter += '_token=' + token + '&_method=DELETE';
    $.ajax({
        type: "post",
        url: url,
        data: dataPeramiter,
        cache: false,
        success: function (data) {
            if (success) {
                success(data);
            }
            return data;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText)
        }
    });
};


getAjax = function (url, data, success) {
    var dataPeramiter = '';
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            newkey = key + '=' + data[key] + '&';
            dataPeramiter += newkey;
        }
    }
    dataPeramiter += '_method=GET';
    $.ajax({
        type: "get",
        url: url,
        data: dataPeramiter,
        cache: false,
        success: function (data) {
            if (success) {
                success(data);
            }
            return data;
        }
    });
};
