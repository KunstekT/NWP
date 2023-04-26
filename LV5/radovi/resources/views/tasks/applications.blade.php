@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Applications for "{{ $task->title }}"</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Student Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $application->user->name }}</td>
                        <td>{{ $application->user->email }}</td>
                        <td>{{ $application->status }}</td>
                        <td>
                            @if ($application->status === 'pending')
                                <form method="POST" action="{{ route('tasks.approve', [$task, $application]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Approve</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection