@if(auth()->user()->hasRole('Admin') || (auth()->user()->hasRole('Shepherd') && $attendee->visitation?->shepherd_id === auth()->id()))
    <form method="POST" action="{{ auth()->user()->hasRole('Admin') 
        ? route('attendees.visitation.request', $attendee)
        : route('attendees.visitation.complete', $attendee) }}">
        @csrf
        @if(auth()->user()->hasRole('Shepherd'))
            @method('PUT')
            <textarea name="shepherd_comment" class="form-control form-control-sm mb-1" placeholder="Your comment..." rows="2">{{ $attendee->visitation->shepherd_comment ?? '' }}</textarea>
            <button type="submit" class="btn btn-sm btn-success w-100">✅ Mark as Done</button>
        @else
            <select name="shepherd_id" class="form-select form-select-sm mb-1">
                <option value="">-- Assign Shepherd --</option>
                @foreach(App\Models\User::role('Shepherd')->get() as $shepherd)
                    <option value="{{ $shepherd->id }}" {{ $attendee->visitation?->shepherd_id == $shepherd->id ? 'selected' : '' }}>
                        {{ $shepherd->first_name }} {{ $shepherd->last_name }}
                    </option>
                @endforeach
            </select>
            <textarea name="admin_comment" class="form-control form-control-sm mb-2" rows="2" placeholder="Admin comment...">{{ $attendee->visitation->admin_comment ?? '' }}</textarea>
            <button type="submit" class="btn btn-sm btn-warning w-100">📌 Request Visit</button>
        @endif
    </form>
@endif
