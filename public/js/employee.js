/**
 * delete one selected employee
 * @param employeeId
 */
function deleteEmployee(employeeId) {
    console.log(employeeId);
    $.ajax({
        url: window.location.pathname + '/' + employeeId,
        type: 'DELETE',
        data: {_token: $('[name=_token]').val()}
    }).done(function (data) {
        console.log(data);
        if (data.error === false) {
            toastr.success(data.msg);
            window.location.reload();
        } else {
            toastr.warning(data.msg);
        }
    }).fail(function (error) {
        console.log(error);
        if (error.status === 403) {
            toastr.warning('This action is unauthorized.');
        } else {
            toastr.warning('Problem with your request ');
        }
    });
}

/**
 * save employee data
 */
function saveEmployee() {
    console.log('save');
    let postData = {
        _token: $('[name=_token]').val(),
        email: $('#employeeEmail').val(),
        password: $('#employeePassword').val(),
    };
    console.log(postData);

    $.post(window.location.pathname, postData)
        .done(function (data) {
            console.log(data);
            if (data.error === false) {
                toastr.success(data.msg);
                window.location.reload();
            } else {
                toastr.warning(data.msg);
            }
        }).fail(function (error) {
        console.log(error);
        if (error.responseJSON !== undefined) {
            let responseText = error.responseJSON;
            let problemField = Object.keys(responseText.errors)[0];
            let errorMsg = responseText.errors[problemField][0];
            console.log(errorMsg);
            toastr.warning('Problem with ' + problemField, errorMsg);
        } else {
            toastr.warning('Problem with your request ');
        }

    });
}
