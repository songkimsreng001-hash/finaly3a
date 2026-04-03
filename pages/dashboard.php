

<?php if ($isAdmin): ?>
<?php $stats = getEventStats(); ?>
<div class="container mt-4">
    <h3 class="fw-bold mb-4">Admin Dashboard <i class="bi bi-speedometer2"></i></h3>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 fw-bold text-success"><?php echo $stats->total_events ?></div>
                    <div class="text-muted small">Total Events</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 fw-bold text-primary"><?php echo $stats->total_registrations ?></div>
                    <div class="text-muted small">Registrations</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 fw-bold text-warning"><?php echo $stats->pending ?></div>
                    <div class="text-muted small">Pending Approvals</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 fw-bold text-info"><?php echo $stats->total_users ?></div>
                    <div class="text-muted small">Registered Users</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">Quick Actions</h5>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="./?page=event/create" class="btn btn-success">
                            <i class="bi bi-calendar-plus"></i> New Event
                        </a>
                        <a href="./?page=events" class="btn btn-outline-success">
                            <i class="bi bi-calendar-event"></i> All Events
                        </a>
                        <a href="./?page=user/list" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">Upcoming Events</h5>
                    <?php
                    $events = getAllEvents();
                    $count  = 0;
                    while ($row = $events->fetch_object()) {
                        if ($count >= 3) break;
                        $count++;
                        echo '<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">' . htmlspecialchars($row->title) . '</div>
                                <small class="text-muted"><i class="bi bi-calendar3"></i> ' .
                                    date('d M Y', strtotime($row->event_date)) . '</small>
                            </div>
                            <span class="badge bg-success rounded-pill">' . $row->reg_count . ' / ' . $row->capacity . '</span>
                        </div>';
                    }
                    if ($count === 0) echo '<p class="text-muted mt-2">No events yet.</p>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="container mt-4">
    <h3 class="fw-bold mb-4">Welcome, <?php echo htmlspecialchars($user->name) ?> <i class="bi bi-hand-wave"></i></h3>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">My Registrations</h5>
                    <?php
                    $myRegs = getMyRegistrations($user->id);
                    $count  = 0;
                    while ($row = $myRegs->fetch_object()) {
                        $count++;
                        $badgeClass = match($row->status) {
                            'approved' => 'bg-success',
                            'rejected' => 'bg-danger',
                            default    => 'bg-warning text-dark',
                        };
                        echo '<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">' . htmlspecialchars($row->title) . '</div>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> ' .
                                    htmlspecialchars($row->location) . ' &nbsp;|&nbsp;
                                 <i class="bi bi-calendar3"></i> ' .
                                    date('d M Y', strtotime($row->event_date)) . '</small>
                            </div>
                            <span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>
                        </div>';
                    }
                    if ($count === 0) {
                        echo '<p class="text-muted mt-2">You have not registered for any events yet.</p>';
                    }
                    ?>
                    <a href="./?page=my-registrations" class="btn btn-sm btn-outline-primary mt-3">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">Quick Links</h5>
                    <div class="d-grid gap-2 mt-3">
                        <a href="./?page=events" class="btn btn-success">
                            <i class="bi bi-calendar-event"></i> Browse Events
                        </a>
                        <a href="./?page=my-registrations" class="btn btn-outline-primary">
                            <i class="bi bi-list-check"></i> My Registrations
                        </a>
                        <a href="./?page=profile" class="btn btn-outline-secondary">
                            <i class="bi bi-person-lines-fill"></i> My Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>