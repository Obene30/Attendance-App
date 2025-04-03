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
            <h5 class="fw-bold mt-2">First Time Registration Form</h5>
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

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth (MM-DD)</label>
                    <input type="text" name="dob" class="form-control" placeholder="e.g. 04-15"
                        pattern="^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$" title="Format: MM-DD" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sex</label>
                    <select name="sex" class="form-select" required>
                        <option value="">-- Select --</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                        <option value="Children">Children</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
