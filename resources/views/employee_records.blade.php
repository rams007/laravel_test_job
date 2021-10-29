@extends('layout.main')

@section('content')

    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Created records
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNewRecordModal">Create
                </button>
            </h5>


            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Image</th>
                    <th scope="col">Category</th>
                </tr>
                </thead>
                <tbody>
                @foreach($allEmployeeRecords as $record)
                    <tr>
                        <th scope="row"><a href="/employee_records/{{$record->id}}"> {{$record->id}} </a></th>
                        <td><a href="/employee_records/{{$record->id}}">{{$record->title}}</a></td>
                        <td><a href="/employee_records/{{$record->id}}"><img style="width: 50px"
                                                                             src="/{{$record->image_path}}"/></a></td>
                        <td><a href="/categories/{{$record->category_id}}"> {{$record->category->name}} </a></td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteRecord({{$record->id}})"> delete</button>
                            <button class="btn btn-info" onclick="editRecord({{$record->id}})"> edit</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            {{$allEmployeeRecords->links('pagination::simple-bootstrap-4')}}

        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="createNewRecordModal" tabindex="-1" aria-labelledby="createNewRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNewRecordModalLabel">Create new record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form in="createEmployee">
                        {{csrf_field()}}
                        <input type="hidden" id="recordId">
                        <div class="row">
                            <div class="mb-3">
                                <label for="recordTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="recordTitle"
                                       placeholder="Please enter title" name="recordTitle">
                            </div>

                            <div class="mb-3">
                                <label for="recordCategory" class="form-label">Category</label>
                                <select class="form-control" id="recordCategory" name="recordCategory">
                                    @foreach($allCategories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="preview" style="display: none">
                                <img id="previewImage" style="    max-height: 150px;">
                            </div>
                            <div class="mb-3">
                                <label for="categoryFile" class="form-label">Please select image </label>
                                <input class="form-control" type="file" id="formFile" accept="image/*">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveRecord()">Save</button>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')
    <script>


        function deleteRecord(recordId) {
            console.log(recordId);
            $.ajax({
                url: window.location.pathname + '/' + recordId,
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
                url: window.location.pathname,

                success: function (data) {
                    console.log(data);
                    if (data.error === false) {
                        toastr.success(data.msg);
                        //            window.location.reload();
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
                url: window.location.pathname + '/' + recordId,
                type: 'GET',
            }).done(function (data) {
                console.log(data);
                if (data.error === false) {
                    $('#preview').show();
                    $('#recordTitle').val(data.data.title);
                    $('#recordCategory').val(data.data.category_id);
                    $('#recordId').val(data.data.id);
                    $('#previewImage').attr('src', '/' + data.data.image_path);

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

    </script>
@endsection
