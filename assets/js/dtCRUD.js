import $ from "jquery";
import "../bundles/datatables/js/datatables"

$(document).ready(function () {
    let datatable = $('#users').data('db');
    $('#users').initDataTables(datatable, {
        'processing': false,
        'serverSide': true
    }).then(function (dt) {
        dt.on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                dt.$('.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        })
        $('#del_user_btn').click(function () {
            let url = $('#del_user_btn').data('path');
            if (dt.row('.selected').id() != null) {
                let data = dt.row('.selected').id();
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    data: {'id': data},
                    success: function (response) {
                        if (response.success) {
                            console.log(response);
                            dt.row('selected').remove().draw(false);
                        } else {
                            console.log(response);
                            alert(response.message + ' ' + response.code);
                        }
                    },
                    error: function (xhr) {
                        let errorInfo = 'Error request: ' + '[' + xhr.status + ' ' + xhr.statusText + ']';
                        console.log('ajaxError xhr:', xhr);
                        alert(errorInfo);
                    }
                });
            } else {
                alert('Select the row to delete!')
            }
        })
        $('#edit_user_btn').click(function () {
            let url = $('#edit_user_btn').data('path');
            if (dt.row('.selected').id() != null) {
                let data = dt.row('.selected').id();
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {id: data},
                    success: function (response) {
                        window.location.href = url + "?id=" + data;
                        console.log(response);
                    },
                    error: function (xhr) {
                        let errorInfo = 'Error request: ' + '[' + xhr.status + ' ' + xhr.statusText + ']';
                        console.log('ajaxError xhr:', xhr);
                        alert(errorInfo);
                    }
                });
            } else {
                alert('Select the row to edit!')
            }
        })
    });
});
