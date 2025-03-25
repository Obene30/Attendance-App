@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">👥 My Assigned Attendees</h2>

    @if($attendees->isEmpty())
        <div class="alert alert-info">You have no assigned attendees yet.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>DOB</th>
                    <th>Sex</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->full_name }}</td>
                        <td>{{ $attendee->address }}</td>
                        <td>{{ $attendee->DOB }}</td>
                        <td>{{ $attendee->sex }}</td>
                        <td>{{ $attendee->category }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $attendees->links() }}
        </div>
    @endif
</div>
@endsection
