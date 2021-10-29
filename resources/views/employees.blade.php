@extends('layout.main')

@section('content')

    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Emploeee section
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNewEmployeeModal">Create
                </button>
            </h5>


            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($allEmployee as $employee)
                    <tr>
                        <th scope="row">{{$employee->id}}</th>
                        <td>{{$employee->email}}</td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteEmployee({{$employee->id}})"> delete</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="createNewEmployeeModal" tabindex="-1" aria-labelledby="createNewEmployeeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNewEmployeeModalLabel">Create new employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form in="createEmployee">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="mb-3">
                                <label for="employeeEmail" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="employeeEmail"
                                       placeholder="name@example.com" name="employeeEmail">
                            </div>
                            <div class="mb-3">
                                <label for="employeePassword" class="form-label">password</label>
                                <input type="password" class="form-control" id="employeePassword" placeholder="password"
                                       name="employeePassword">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveEmployee()">Save</button>
                </div>
            </div>
        </div>
    </div>




@endsection


@section('scripts')
    <script>

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
                toastr.warning('Problem with your request ');
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

    </script>
@endsection
