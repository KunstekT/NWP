@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Available Tasks') }}</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Language') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ __('tasks.' . $task->language) }}</td>
                        <td>{{ __('tasks.' . $task->type) }}</td>
                        <td>
                            <form method="POST" action="{{ route('tasks.signUpForTask', ['task' => $task->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">{{ __('Sign Up') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection