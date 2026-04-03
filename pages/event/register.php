<?php

$id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    header('Location: ./?page=events');
    exit;
}
if ($event->reg_count >= $event->capacity) {
    header('Location: ./?page=event/detail&id=' . $id);
    exit;
}
if (isUserRegistered($event->id, $user->id)) {
    header('Location: ./?page=event/detail&id=' . $id);
    exit;
}

$noteErr = '';
$note    = '';

if (isset($_POST['submit'])) {
    $note = trim($_POST['note'] ?? '');

    if (registerForEvent($event->id, $user->id, $note)) {
        echo '<div class="alert alert-success container mt-4">
            <i class="bi bi-check-circle-fill"></i>
            You have successfully registered for <strong>' . htmlspecialchars($event->title) . '</strong>!
            Your registration is <strong>pending approval</strong> from the admin.
            <br><a href="./?page=my-registrations">View my registrations</a>
        </div>';
        return;
    } else {
        echo '<div class="alert alert-danger container mt-4">Registration failed. Please try again.</div>';
    }
}
?>
<div class="container mt-4">
    <a href="./?page=event/detail&id=<?php echo $event->id ?>" class="btn btn-sm btn-outline-warning mb-3">
        <i class="bi bi-arrow-left"></i> Back to Event
    </a>

    <div class="col-md-8 col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1"><i class="bi bi-calendar-check text-success"></i> Register for Event</h4>
                <p class="text-muted mb-3"><?php echo htmlspecialchars($event->title) ?></p>
                <hr>

                <div class="mb-3 p-3 bg-light rounded">
                    <div class="row g-2 text-sm">
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-success"></i>
                            <?php echo htmlspecialchars($event->location) ?>
                        </div>
                        <div class="col-6">
                            <i class="bi bi-calendar3 text-success"></i>
                            <?php echo date('d M Y', strtotime($event->event_date)) ?>
                        </div>
                        <div class="col-6">
                            <i class="bi bi-clock text-success"></i>
                            <?php echo date('H:i', strtotime($event->event_date)) ?>
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-success"></i>
                            <?php echo $event->capacity - $event->reg_count ?> seats left
                        </div>
                    </div>
                </div>

                <form method="post" action="./?page=event/register&id=<?php echo $event->id ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Your Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user->name) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user->username) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Note <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="note" class="form-control" rows="3"
                                  placeholder="Any special requirements or message for the organizer..."
                        ><?php echo htmlspecialchars($note) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg"></i> Confirm Registration
                        </button>
                        <a href="./?page=event/detail&id=<?php echo $event->id ?>"
                           class="btn btn-outline-danger px-4">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>