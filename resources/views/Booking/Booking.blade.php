<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Lesson - TutorLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 fw-bold">📅 Request Lesson with {{ $tutor->first_name }} {{ $tutor->last_name }}</h3>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('Booking.store') }}" method="POST">
                        @csrf

                        <!-- Hidden Tutor Profile ID -->
                        <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Introductory Message -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Message for the Tutor</label>
                            <textarea class="form-control" name="message" rows="4" 
                                      placeholder="Introduce yourself, discuss what you want to learn, or add any requests..." required>{{ old('message') }}</textarea>
                        </div>

                        <!-- Dynamic Time Slots Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Proposed Date & Time Slots</label>
                            <small class="text-muted d-block mb-3">You can propose more than one date and time option for your lesson.</small>

                            <!-- Slots Container -->
                            <div id="slots-container">
                                <div class="row g-2 mb-2 slot-row">
                                    <div class="col-md-5">
                                        <label class="form-label small text-muted mb-1">Date</label>
                                        <input type="date" name="dates[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small text-muted mb-1">Time</label>
                                        <input type="time" name="times[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <!-- Hide remove button on the very first row -->
                                        <button type="button" class="btn btn-danger w-100 remove-slot-btn" style="display: none;">Delete</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Plus Button -->
                            <button type="button" id="add-slot-btn" class="btn btn-outline-secondary btn-sm mt-2">
                                ➕ Add Another Slot
                            </button>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('Search.Tutor_View') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-success px-4">Confirm Booking Request</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- JavaScript to handle dynamic adding/removing of date-time fields -->
<script>
    const addSlotBtn = document.getElementById('add-slot-btn');
    const slotsContainer = document.getElementById('slots-container');

    addSlotBtn.addEventListener('click', function() {
        // Clone the first slot row
        const firstRow = document.querySelector('.slot-row');
        const newRow = firstRow.cloneNode(true);

        // Reset the inputs in the cloned row
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Show and configure the delete button on the cloned row
        const removeBtn = newRow.querySelector('.remove-slot-btn');
        removeBtn.style.display = 'block';

        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });

        // Append the new row to container
        slotsContainer.appendChild(newRow);
    });
</script>

</body>
</html>