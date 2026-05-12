@extends('layouts.app')
@section('page-title', 'Edit User')

@section('content')
    @include('users.create', ['user' => $user])
@endsection
