@extends('template')
@section('title', 'Login')
@section('header', 'Login')

@section('content')

<form method="POST" action="{{ route('login_post') }}">
    {!! csrf_field() !!}
    
    <p>
        <label>Username:</label>
        <input class="form-control" type="text" name="username" />
    </p>
    <p>
        <label>Password:</label>
        <input class="form-control" type="password" name="password" />
    </p>
    <p>
        <button class="btn btn-primary" type="submit">Sign in</button>
    </p>
</form>

@stop