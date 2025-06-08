@extends('layouts.app')

@section('content')
    <div class="container">   
        <h1>Welcome to the Laravel Application</h1>
        <p>This is a simple welcome page.</p>
        <p>Feel free to explore the application!</p>
        <p>Current date and time: {{ now() }}</p>
        <p>Current user: {{ auth()->user() ? auth()->user()->name : 'Guest' }}</p>
    </div>
@endsection