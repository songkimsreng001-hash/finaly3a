<?php

$titleErr = $descErr = $locErr = $dateErr = $capErr = '';
$title = $desc = $loc = $date = '';
$capacity = 50;

if (isset($_POST['submit'])) {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $loc = trim($_POST['location'] ?? '');
    $date = trim($_POST['event_date'] ?? '');
    $capacity = (int) ($_POST['capacity'] ?? 50);
    $photo = $_FILES['image'] ?? [];

    if (empty($title))
        $titleErr = 'Please enter event title.';
    if (empty($desc))
        $descErr = 'Please enter description.';
    if (empty($loc))
        $locErr = 'Please enter location.';
    if (empty($date))
        $dateErr = 'Please select date and time.';
    if ($capacity < 1)
        $capErr = 'Capacity must be at least 1.';

    if (!$titleErr && !$descErr && !$locErr && !$dateErr && !$capErr) {
        try {
            if (createEvent($title, $desc, $loc, $date, $capacity, $photo)) {
                echo '<div class="alert alert-success container mt-4">
                    Event created! <a href="./?page=events">View events</a>
                </div>';
                $title = $desc = $loc = $date = '';
                $capacity = 50;
            } else {
                echo '<div class="alert alert-danger container mt-4">Failed to create event.</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger container mt-4">' . $e->getMessage() . '</div>';
        }
    }
}
?>
<div class="container mt-4">
    <a href="./?page=events" class="btn btn-sm btn-outline-warning mb-3">
        <i class="bi bi-arrow-left"></i> Back
    </a>
    <div class="col-md-8 col-lg-7 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-calendar-plus text-success"></i> Create Event</h4>

                <form method="post" action="./?page=event/create" enctype="multipart/form-data">
                    <div class="mb-3 text-center">
                        <input type="file" name="image" id="eventImg" hidden accept=".jpg,.jpeg,.png">
                        <label for="eventImg" role="button">
                            <img id="imgPreview" src="./assets/images/loading.png" class="img-fluid rounded shadow-sm"
                                style="max-height:200px;object-fit:cover;min-width:100%;">
                        </label>
                        <div class="small text-muted mt-1">Click image to upload banner (JPG, JPEG, PNG — max 500KB)
                            (optional)</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input name="title" value="<?php echo htmlspecialchars($title) ?>" type="text"
                            class="form-control <?php echo $titleErr ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?php echo $titleErr ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="desc" rows="4"
                            class="form-control <?php echo $descErr ? 'is-invalid' : '' ?>"><?php echo htmlspecialchars($desc) ?></textarea>
                        <div class="invalid-feedback"><?php echo $descErr ?></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Location</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input name="location" value="<?php echo htmlspecialchars($loc) ?>" type="text"
                                    class="form-control <?php echo $locErr ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback"><?php echo $locErr ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date &amp; Time</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input name="event_date" value="<?php echo htmlspecialchars($date) ?>"
                                    type="datetime-local"
                                    class="form-control <?php echo $dateErr ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback"><?php echo $dateErr ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Capacity (max seats)</label>
                        <div class="input-group" style="max-width:200px;">
                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                            <input name="capacity" value="<?php echo $capacity ?>" type="number" min="1"
                                class="form-control <?php echo $capErr ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback"><?php echo $capErr ?></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="submit" id="submitBtn" class="btn btn-success px-4">
                            <i class="bi bi-check-lg"></i> Create Event
                        </button>
                        <a href="./?page=events" class="btn btn-outline-secondary">Cancel</a>
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

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const maxSize = 500000;

        if (file.size > maxSize) {
            alert('File size is too large! Maximum allowed size is 500KB.');
            this.value = '';
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file type. Only JPG, JPEG, and PNG are allowed.');
            this.value = '';
            return;
        }

        document.getElementById('imgPreview').src = URL.createObjectURL(file);
    });
</script>

<script>
    $(document).ready(function () {
        $('#submitBtn').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Do you want to save the changes?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Save",
                denyButtonText: `Don't save`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('form').submit(); // ✅ Properly submits the form
                }
            });
        });
    });
</script>