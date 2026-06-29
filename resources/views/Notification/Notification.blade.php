<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - TutorLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Top Navigation -->
<nav class="navbar navbar-dark bg-secondary mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="javascript:history.back()">← Back</a>
    </div>
</nav>

<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0">🔔 Your Notifications</h4>
                </div>
                <div class="card-body p-4">

                    @forelse($notifications as $notif)
                        <!-- New/Unread notifications get a light background with a primary blue border -->
                        <div class="p-3 mb-2 rounded border-start border-4 {{ $notif->is_read ? 'bg-white border-secondary' : 'bg-light border-primary fw-bold' }}">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 text-primary">{{ $notif->title }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</small>
                            </div>
                            <p class="text-secondary mb-2">{{ $notif->message }}</p>
                            <a href="{{ $notif->action_url }}" class="btn btn-sm btn-outline-primary py-0 px-2 fs-6">View Detail</a>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No notifications found.</p>
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>