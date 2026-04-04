<?php

$id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    header('Location: ./?page=events');
    exit;
}

if (isset($_POST['reg_id'], $_POST['status'])) {
    $allowed = ['approved', 'rejected', 'pending'];
    $status  = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'pending';

    updateRegistrationStatus((int)$_POST['reg_id'], $status);

    $_SESSION['alert'] = $status;

    header('Location: ./?page=event/registrations&id=' . $id);
    exit;
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">

    <a href="./?page=event/detail&id=<?php echo $event->id ?>"
       class="btn btn-sm btn-outline-warning mb-3">
        <i class="bi bi-arrow-left"></i> Back to Event
    </a>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">
                Registrations <i class="bi bi-list-check"></i>
            </h4>
            <small class="text-muted"><?php echo htmlspecialchars($event->title) ?></small>
        </div>
        <span class="badge bg-success fs-6">
            <?php echo $event->reg_count ?> / <?php echo $event->capacity ?>
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Registered</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $regs  = getEventRegistrations($id);
            $count = 0;

            while ($row = $regs->fetch_object()):
                $count++;

                $badgeClass = match($row->status) {
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    default    => 'bg-warning text-dark',
                };
            ?>
                <tr>
                    <td><?php echo $count ?></td>

                    <td>
                        <img src="<?php echo $row->photo ?? './assets/images/Profile_PNG.png' ?>"
                             height="40"
                             class="rounded-circle img-thumbnail"
                             style="width:40px;object-fit:cover;"
                             onerror="this.src='./assets/images/Profile_PNG.png'">
                    </td>

                    <td><?php echo htmlspecialchars($row->name) ?></td>
                    <td class="text-muted">@<?php echo htmlspecialchars($row->username) ?></td>

                    <td class="text-muted small">
                        <?php echo date('d M Y H:i', strtotime($row->created_at)) ?>
                    </td>

                    <td class="text-muted small">
                        <?php echo $row->note ? htmlspecialchars($row->note) : '—' ?>
                    </td>

                    <td>
                        <span class="badge <?php echo $badgeClass ?>">
                            <?php echo ucfirst($row->status) ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($row->status !== 'approved'): ?>
                        <form method="post"
                              action="./?page=event/registrations&id=<?php echo $id ?>"
                              class="d-inline js-confirm">

                            <input type="hidden" name="reg_id" value="<?php echo $row->id ?>">
                            <input type="hidden" name="status" value="approved">

                            <button type="submit"
                                    class="btn btn-sm btn-success"
                                    title="Approve">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php if ($row->status !== 'rejected'): ?>
                        <form method="post"
                              action="./?page=event/registrations&id=<?php echo $id ?>"
                              class="d-inline js-confirm">

                            <input type="hidden" name="reg_id" value="<?php echo $row->id ?>">
                            <input type="hidden" name="status" value="rejected">

                            <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    title="Reject">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                        <?php endif; ?>

                    </td>
                </tr>

            <?php endwhile; ?>

            <?php if ($count === 0): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        No registrations yet for this event.
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
<script>
document.querySelectorAll('.js-confirm').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let status = this.querySelector('input[name="status"]').value;

        let message = status === 'approved'
            ? 'Approve this registration?'
            : 'Reject this registration?';

        let confirmColor = status === 'approved' ? '#28a745' : '#dc3545';

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, confirm!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
<?php if (isset($_SESSION['alert'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: 'Status updated successfully!'
});
</script>
<?php unset($_SESSION['alert']); endif; ?>