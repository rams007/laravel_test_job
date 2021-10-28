@extends('layout.main')

@section('content')

    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Created records by categories        </h5>


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

@endsection

