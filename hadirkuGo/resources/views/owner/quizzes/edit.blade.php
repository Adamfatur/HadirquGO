@extends('layout.owner')

@section('title', 'Edit Quiz')
@section('page-title', 'Edit Quiz')

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

    <form action="{{ route('owner.quizzes.update', [$business->business_unique_id, $quiz->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="quizTitle" class="form-label">Quiz Title</label>
            <input type="text"
                   name="title"
                   id="quizTitle"
                   class="form-control"
                   value="{{ old('title', $quiz->title) }}"
                   required>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Update Quiz
        </button>
    </form>
@endsection