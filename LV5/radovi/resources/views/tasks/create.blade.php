@extends('layouts.app')

@sec ton('content')
    <div class="container">
        <h1>{{ __('tasks.task_form_title') }}</h1>
        <form action="{ route('tasks.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="title">{{ __('tasks.title') }}:</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-roup">
                <label for="title_en">{{ __('tasks.title_en') }}:</label>
                <input type="text" name="title_en" id="title_en" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">{{ __('tasks.description') }}:</label>
                <textaea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="decription_en">{{ __('tasks.description_en') }}:</label>
                <textarea name="description_en" id="description_en" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="type">{{ __('tasks.type') }}:</label>
                <select name="type" id="type" class="form-control">
                    <option value="stručni">{{ __('tasks.type_stručni') }}</option>
                    <option value="preddiplomski">{{ __('tasks.type_preddiplomski') }}</option>
                    <option value="diplomski">{{ __('tasks.type_diplomski') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('tasks.submit_button') }}</button>
        </form>

    </div>
@endsection