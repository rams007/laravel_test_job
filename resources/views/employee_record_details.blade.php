@extends('layout.main')

@section('content')

    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Record Details </h5>

            <table class="table">
                <tbody>
                <tr>
                    <td>Id</td>
                    <td scope="row">{{$user_record->id}}</td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td scope="row">{{$user_record->title}}</td>
                </tr>
                <tr>
                    <td>Image</td>
                    <td scope="row"><img style="width: 50px" src="/{{$user_record->image_path}}"/></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td scope="row"><a
                            href="/categories/{{$user_record->category_id}}"> {{$user_record->category->name}} </a></td>
                </tr>
                <tr>
                    <td>Author</td>
                    <td scope="row"><a href="/employee/{{$user_record->user_id}}"> {{$user_record->author->email}}</a>
                    </td>
                </tr>
                </tbody>
            </table>


        </div>
    </div>




@endsection


@section('scripts')
    <script>


    </script>
@endsection
