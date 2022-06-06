$(document).ready(function () {
    getConnectionCounts();
    getSuggestions();

    $("input[name='btnradio']").change(function () {
        id = $(this).attr('id');
        switch (id) {
            case 'btnradio1':
                getSuggestions();
                break;
            case 'btnradio2':
                getSentRequests();
                break;
            case 'btnradio3':
                getReceivedRequests();
                break;
            case 'btnradio4':
                confirmedConnections();
                break;
        }
    });
});


function getSuggestions() {
    showLoading();
    getAjax('/connections', {}, function (data) {
        $('#content').html(data);
        // console.table(data);
    });
}

function getSentRequests() {
    showLoading();
    getAjax('/connections/sent_req', {}, function (data) {
        $('#content').html(data);
        // console.table(data);
    });
}

function getReceivedRequests() {
    showLoading();
    getAjax('/connections/received_req', {}, function (data) {
        $('#content').html(data);
        // console.table(data);
    });
}

function confirmedConnections() {
    showLoading();
    getAjax('/connections/confirmed', {}, function (data) {
        $('#content').html(data);
        // console.table(data);
    });
}

function requestConnect(user_id) {
    $('#suggestion_box_' + user_id).remove();
    postAjax('/connections', {user_id: user_id}, function (data) {
        getConnectionCounts();
    });
}

function withdrawRequest(user_id) {
    if (confirm("Are you sure?")) {
        deleteAjax('/connections/' + user_id, {}, function (data) {
            $('#request_box_' + user_id).remove();
            getConnectionCounts();
        });
    }
    return false;
}

function acceptRequest(user_id) {
    patchAjax('/connections/' + user_id, {}, function (data) {
        $('#request_box_' + user_id).remove();
        getConnectionCounts();
    });
}

function removeConnection(id) {
    if (confirm("Are you sure?")) {
        $('#connection_box_' + id).remove();
         deleteAjax('/connections/' + id, {'remove_connection':'Yes'}, function (data) {
             getConnectionCounts();
         });
    }
    return false;
}

function getConnectionCounts() {
    getAjax('/connection_counts', {}, function (data) {
        $('#suggestions_count').html(data.suggestionsCount);
        $('#sent_requests_count').html(data.sentRequestsCount);
        $('#received_requests_count').html(data.receiveRequestsCount);
        $('#connections_count').html(data.connectionsCount);
    });
}

function showLoading() {
    loading = '<div class="d-flex align-items-center  mb-2  text-white bg-dark p-1 shadow" style="height: 45px"><strong class="ms-1 text-primary">Loading...</strong><div class="spinner-border ms-auto text-primary me-4" role="status" aria-hidden="true"></div></div>';
    $('#content').html(loading);
}

function hideLoading() {
    loading = '';
    $('#content').html(loading);
}

