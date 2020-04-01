@extends('layouts.main')

@section('content')
<div class="card">
    <ul class="list-group list-group-flush text-left">
        <li class="list-group-item">{{ $meeting->title }}</li>
        <li class="list-group-item">{{ $meeting->description }}</li>
        <li class="list-group-item">{{ $meeting->time }}</li>
        <li class="list-group-item">
            <form action="{{ $meeting->id }}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-0">Delete</button>
            </form>
        </li>
    </ul>
</div>
@endsection