@extends('layout.main')

@section('content')
    <form class="form-signin" method="post">
        {{csrf_field()}}
        <h2 class="form-signin-heading">Please register as manager</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
        <p>Already registered? <a href="/login">Login here</a></p>
    </form>
@endsection
