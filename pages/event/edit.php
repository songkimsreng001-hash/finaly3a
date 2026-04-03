<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    header('Location: ./?page=events');
    exit;
}

if (isset($_POST['reg_id'], $_POST['status'])) {
    $allowed = ['approved', 'rejected'];
    $status  = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'pending';

    updateRegistrationStatus((int)$_POST['reg_id'], $status);

    echo "
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Status updated successfully'
    }).then(() => {
        window.location.href = './?page=event/registrations&id=$id';
    });
    </script>
    ";
    exit;
}

$regs = getEventRegistrations($id);
?>

<div class="container mt-4">

    <a href="./?page=event/detail&id=<?php echo $event->id ?>"
       class="btn btn-sm btn-outline-warning mb-3">
        <i class="bi bi-arrow-left"></i> Back
    </a>

    <h4 class="fw-bold mb-3">Registrations</h4>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>

            <tbody>
            <?php $i=1; while ($row = $regs->fetch_object()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row->name) ?></td>

                    <td>
                        <span class="badge 
                            <?= $row->status=='approved'?'bg-success':
                                ($row->status=='rejected'?'bg-danger':'bg-warning') ?>">
                            <?= $row->status ?>
                        </span>
                    </td>

                    <td>
                        <form method="post" class="d-inline action-form">
                            <input type="hidden" name="reg_id" value="<?= $row->id ?>">
                            <input type="hidden" name="status" value="approved">
                            <button type="button" class="btn btn-success btn-sm btn-approve">✔</button>
                        </form>

                        <form method="post" class="d-inline action-form">
                            <input type="hidden" name="reg_id" value="<?= $row->id ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button type="button" class="btn btn-danger btn-sm btn-reject">✖</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
document.querySelectorAll('.btn-approve').forEach(btn => {
    btn.onclick = () => {
        let form = btn.closest('form');

        Swal.fire({
            title: 'Approve user?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754'
        }).then(r => {
            if (r.isConfirmed) form.submit();
        });
    }
});

document.querySelectorAll('.btn-reject').forEach(btn => {
    btn.onclick = () => {
        let form = btn.closest('form');

        Swal.fire({
            title: 'Reject user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545'
        }).then(r => {
            if (r.isConfirmed) form.submit();
        });
    }
});
</script>