function deleteRecord(recordId) {
    console.log(recordId);
    $.ajax({
        url: employeeRecordsUrl + '/' + recordId,
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


function saveRecord() {
    console.log('save');

    var formData = new FormData();

    // add assoc key values, this will be posts values
    formData.append("image", $('#formFile')[0].files[0]);
    formData.append("id", $('#recordId').val());
    formData.append("title", $('#recordTitle').val());
    formData.append("category_id", $('#recordCategory').val());
    formData.append("_token", $('[name=_token]').val());


    console.log(formData);

    $.ajax({
        type: "POST",
        url: employeeRecordsUrl,

        success: function (data) {
            console.log(data);
            if (data.error === false) {
                toastr.success(data.msg);
                window.location.reload();
            } else {
                toastr.warning(data.msg);
            }
        },
        error: function (error) {
            console.log(error);
            if (error.status === 403) {
                toastr.warning('This action is unauthorized.');
            } else {
                if (error.responseJSON !== undefined) {
                    let responseText = error.responseJSON;
                    let problemField = Object.keys(responseText.errors)[0];
                    let errorMsg = responseText.errors[problemField][0];
                    console.log(errorMsg);
                    toastr.warning('Problem with ' + problemField, errorMsg);
                } else {
                    toastr.warning('Problem with your request ');
                }
            }
        },

        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
}

function editRecord(recordId) {
    $.ajax({
        url: employeeRecordsUrl + '/' + recordId,
        type: 'GET',
    }).done(function (data) {
        console.log(data);
        if (data.error === false) {
            $('#preview').show();
            $('#recordTitle').val(data.data.title);
            $('#recordCategory').val(data.data.category_id);
            $('#recordId').val(data.data.id);
            $('#previewImage').attr('src', '/storage/' + data.data.image_path);

            var myModal = new bootstrap.Modal(document.getElementById('createNewRecordModal'), {})
            myModal.show()
        } else {
            toastr.warning(data.msg);
        }
    }).fail(function (error) {
        console.log(error);
        toastr.warning('Problem with your request ');
    });

}
