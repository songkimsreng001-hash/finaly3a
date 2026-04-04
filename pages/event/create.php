<?php

$title = $desc = $loc = $date = '';
$capacity = 50;

$titleErr = $descErr = $locErr = $dateErr = $capErr = '';
$errorMsg = '';

if (isset($_POST['submit'])) {

    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $loc = trim($_POST['location'] ?? '');
    $date = trim($_POST['event_date'] ?? '');
    $capacity = (int) ($_POST['capacity'] ?? 50);
    $image = $_FILES['image'] ?? null;

    if ($title === '')
        $titleErr = 'Please enter title';
    if ($desc === '')
        $descErr = 'Please enter description';
    if ($loc === '')
        $locErr = 'Please enter location';
    if ($date === '')
        $dateErr = 'Please select date';
    if ($capacity < 1)
        $capErr = 'Capacity must be at least 1';

    if (!$titleErr && !$descErr && !$locErr && !$dateErr && !$capErr) {

        try {
            if (createEvent($title, $desc, $loc, $date, $capacity, $image)) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Event created successfully',
                    icon: 'success',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    window.location.href = './?page=events';
                });

                // ✅ fallback redirect (always works)
                setTimeout(() => {
                    window.location.href = './?page=events';
                }, 1500);
                </script>
                ";
exit;
            }

        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
    }
}
?>

<div class="container mt-4">
    <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?= $errorMsg ?></div>
    <?php endif; ?>
    <a href="./?page=events" class="btn btn-sm btn-outline-warning mb-3">
        <i class="bi bi-arrow-left"></i> Back
    </a>
    <div class="col-md-8 col-lg-7 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-pencil-square text-primary"></i> Create Event</h4>
                <form method="POST" enctype="multipart/form-data">

                    <button type="submit" name="submit" id="realSubmit" hidden></button>

                    <div class="mb-3 text-center">
                        <input type="file" name="image" id="eventImg" hidden accept=".jpg,.jpeg,.png">

                        <label for="eventImg" style="cursor:pointer;">
                            <img id="imgPreview" src="./assets/images/loading.png" class="img-fluid rounded shadow-sm"
                                style="max-height:200px; width:100%; object-fit:cover;">
                        </label>

                        <div class="small text-muted mt-2">
                            Click image to upload
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"
                            class="form-control <?= $titleErr ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= $titleErr ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="desc" rows="4"
                            class="form-control <?= $descErr ? 'is-invalid' : '' ?>"><?= htmlspecialchars($desc) ?></textarea>
                        <div class="invalid-feedback"><?= $descErr ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Location</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="location" value="<?= htmlspecialchars($loc) ?>"
                                    class="form-control <?= $locErr ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback"><?= $locErr ?></div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date & Time</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="datetime-local" name="event_date" value="<?= htmlspecialchars($date) ?>"
                                    class="form-control <?= $dateErr ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback"><?= $dateErr ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Capacity</label>
                        <div class="input-group" style="max-width:200px;">
                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                            <input type="number" name="capacity" min="1" value="<?= $capacity ?>"
                                class="form-control <?= $capErr ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback"><?= $capErr ?></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" id="submitBtn" class="btn btn-outline-success px-4">
                            <i class="bi bi-check-lg"></i> Create Event
                        </button>
                        <a href="./?page=events" class="btn btn-outline-danger px-4">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<script>

    document.getElementById('eventImg').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        if (file.size > 500000) {
            alert('Max file size is 500KB');
            this.value = '';
            return;
        }

        document.getElementById('imgPreview').src = URL.createObjectURL(file);
    });

    document.getElementById('submitBtn').addEventListener('click', function () {

        Swal.fire({
            title: 'Create this event?',
            text: 'Please confirm all information',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, create it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('realSubmit').click();
            }
        });

    });
</script>