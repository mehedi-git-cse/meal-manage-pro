@extends('layouts.app')
@section('page-title', 'Edit Bazar Entry')

@section('content')
    @include('bazar.create', ['bazarEntry' => $bazarEntry])
@endsection
