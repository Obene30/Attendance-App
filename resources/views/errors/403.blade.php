@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>🚫 Access Denied</h1>
    <p>Sorry, you do not have permission to access this page.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go Back</a>
</div>
@endsection
