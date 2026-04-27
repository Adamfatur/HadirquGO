@extends('layout.student')

@section('content')
    <div class="container">
        <h1>{{ __('Edit') }} Profile</h1>
        <form action="{{ route('student.profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->biodata->phone_number ?? '') }}">
            </div>
            <div class="form-group">
                <label for="id_number">ID Number</label>
                <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $user->biodata->id_number ?? '') }}">
            </div>
            <div class="form-group">
                <label for="nickname">Nickname</label>
                <input type="text" name="nickname" class="form-control" value="{{ old('nickname', $user->biodata->nickname ?? '') }}">
            </div>
            <div class="form-group">
                <label for="about">{{ __('About') }}</label>
                <textarea name="about" class="form-control">{{ old('about', $user->biodata->about ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="verified">Verified</label>
                <input type="checkbox" name="verified" value="1" {{ old('verified', $user->biodata->verified) ? 'checked' : '' }}>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
