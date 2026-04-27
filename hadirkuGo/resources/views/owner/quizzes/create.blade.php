@extends('layout.owner')

@section('title', 'Create New Quiz')
@section('page-title', 'Create New Quiz')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Please fix the following errors:
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.quizzes.store', $business->business_unique_id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="quizTitle" class="form-label">Quiz Title</label>
            <input type="text"
                   name="title"
                   id="quizTitle"
                   class="form-control"
                   value="{{ old('title') }}"
                   required>
        </div>
        <button type="submit" class="btn btn-success">
            <i class="fa fa-save"></i> Create Quiz
        </button>
    </form>
@endsection