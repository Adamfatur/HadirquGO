<!-- resources/views/student/qrcode/choose_team.blade.php -->

@extends('layout.lecturer')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; height: 70vh; background-color: #f0f2f5;">
        <div style="text-align: center; padding: 20px; max-width: 400px; width: 100%; background-color: #ffffff; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="margin-bottom: 15px;">Pilih Tim</h2>
            <p style="color: #666666; margin-bottom: 20px;">Silakan pilih tim Anda untuk melakukan {{ __('Check-In') }}</p>

            <form action="{{ route('lecturer.qrcode.checkin') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <select id="team_id" name="team_id" required style="width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 5px;">
                        <option value="" disabled selected>-- Pilih Tim --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: #ffffff; border: none; border-radius: 5px; cursor: pointer;">
                    Generate QR Code
                </button>
            </form>

            <button onclick="window.history.back()" style="width: 100%; margin-top: 10px; padding: 10px; background: none; color: #007bff; border: none; cursor: pointer;">
                Batal
            </button>
        </div>
    </div>
@endsection
