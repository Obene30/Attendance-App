@php
    $firstVisitation = is_iterable($attendee->visitation) ? $attendee->visitation->first() : null;
@endphp

@if(auth()->user()->hasRole('Admin'))
    <form method="POST" action="{{ route('attendees.visitation.request', $attendee) }}">
        @csrf

        <label class="form-label small mb-1">Assign Shepherd(s)</label>
        <select name="shepherd_ids[]" class="form-select form-select-sm mb-2" multiple>
            @foreach(App\Models\User::role('Shepherd')->get() as $shepherd)
                <option value="{{ $shepherd->id }}"
                    @if($attendee->visitation && $attendee->visitation->pluck('shepherd_id')->contains($shepherd->id)) selected @endif>
                    {{ $shepherd->first_name }} {{ $shepherd->last_name }}
                </option>
            @endforeach
        </select>

        <label class="form-label small mb-1">Admin Comment</label>
        <textarea name="admin_comment" class="form-control form-control-sm mb-2" rows="2" placeholder="Admin comment...">{{ $firstVisitation?->admin_comment }}</textarea>

        <button type="submit" class="btn btn-sm btn-warning w-100">📌 Request Visit</button>
    </form>

@elseif(auth()->user()->hasRole('Shepherd') && $attendee->visitation && $attendee->visitation->where('shepherd_id', auth()->id())->first())
    @php
        $shepherdVisitation = $attendee->visitation->where('shepherd_id', auth()->id())->first();
    @endphp
    <form method="POST" action="{{ route('attendees.visitation.complete', $attendee) }}">
        @csrf
        @method('PUT')

        <label class="form-label small mb-1">Your Comment</label>
        <textarea name="shepherd_comment" class="form-control form-control-sm mb-2" placeholder="Your comment..." rows="2">{{ $shepherdVisitation->shepherd_comment ?? '' }}</textarea>

        <button type="submit" class="btn btn-sm btn-success w-100">✅ Mark as Done</button>
    </form>
@endif
