@extends('layouts.main')

@section('content')
<div class="table-responsive">
    <div>
        <a href="" class="btn btn-outline-primary rounded-0 float-left mb-3">Tambah</a>
    </div>
    <table class="table table-striped table-bordered">
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($meetings as $meeting)
                <tr>
                    <td>{{ $meeting->title }}</td>
                    <td>
                        <a href="/meeting/{{ $meeting->id }}" class="btn btn-outline-info btn-sm rounded-0">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection