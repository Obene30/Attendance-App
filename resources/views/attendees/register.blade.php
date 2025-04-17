<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendee Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 12px;
        }

        .card-header img {
            width: 70px;
        }

        .card-header h5 {
            margin-top: 10px;
        }

        @media (max-width: 576px) {
            .card-header img {
                width: 60px;
            }

            .card {
                margin: 1rem;
            }

            .card-header h5 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-header bg-warning text-center text-dark fw-bold">
            <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Church Logo" class="img-fluid">
            <h5 class="fw-bold mt-2">First Timers Welcoming Form</h5>
        </div>

        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success text-center fw-semibold">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please correct the errors below.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('attendee.store') }}">
                @csrf

                <!-- Basic Info -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Birthday (MM-DD)</label>
                    <input type="text" name="dob" class="form-control" placeholder="e.g. 04-21"
                           pattern="^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$" title="Format: MM-DD" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sex</label>
                    <select name="sex" class="form-select" required>
                        <option value="">-- Select --</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <option value="Adults">Adults</option>
                        <option value="Children <13">Children &lt;13</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                </div>
                

                <!-- Extra Questions -->
                <div class="mb-3">
                    <label class="form-label">Are you happy to be contacted by us?</label>
                    <select name="contact_consent" class="form-select">
                        <option value="">-- Optional --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Invited to church by?</label>
                    <input type="text" name="invited_by" class="form-control" placeholder="Optional">
                </div>

                <div class="mb-3">
                    <label class="form-label">Please select an option below</label>
                    <select name="visit_purpose" class="form-select" id="visit_purpose_select">
                        <option value="">-- Optional --</option>
                        <option value="I am happy to stay and fellowship">I am happy to stay and fellowship</option>
                        <option value="I am just visiting">I am just visiting</option>
                        <option value="I am new to church and want to learn more about Christianity">I am new to church and want to learn more about Christianity</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" name="visit_purpose_other" class="form-control mt-2 d-none" id="visit_purpose_other_input" placeholder="Please specify if Other">
                </div>

                <div class="mb-3">
                    <label class="form-label">What did you enjoy about the service today?</label>
                    <select name="enjoyed_service" class="form-select" id="enjoyed_service_select">
                        <option value="">-- Optional --</option>
                        <option value="Location of the church">Location of the church</option>
                        <option value="Praise and Worship">Praise and Worship</option>
                        <option value="Preaching">Preaching</option>
                        <option value="Welcoming reception from the people">Welcoming reception from the people</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" name="enjoyed_service_other" class="form-control mt-2 d-none" id="enjoyed_service_other_input" placeholder="Please specify if Other">
                </div>

                <div class="mb-4">
                    <label class="form-label">Any other feedback?</label>
                    <textarea name="other_feedback" class="form-control" rows="3" placeholder="Your feedback (optional)"></textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Show text input if "Other" is selected
    document.getElementById('visit_purpose_select').addEventListener('change', function () {
        const input = document.getElementById('visit_purpose_other_input');
        input.classList.toggle('d-none', this.value !== 'Other');
    });

    document.getElementById('enjoyed_service_select').addEventListener('change', function () {
        const input = document.getElementById('enjoyed_service_other_input');
        input.classList.toggle('d-none', this.value !== 'Other');
    });
</script>

</body>
</html>
