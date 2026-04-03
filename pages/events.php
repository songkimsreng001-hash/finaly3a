<?php
if (!isset($isAdmin)) {
    $isAdmin = isset($_SESSION['user']) && $_SESSION['user']->level === 'admin';
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Events <i class="bi bi-calendar-event"></i></h3>
        <?php if ($isAdmin): ?>
            <a href="./?page=event/create" class="btn btn-success">
                <i class="bi bi-calendar-plus"></i> New Event
            </a>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php
        $events = getAllEvents();
        $count = 0;
        while ($row = $events->fetch_object()):
            $count++;
            $isFull = $row->reg_count >= $row->capacity;
            $dateStr = date('d M Y · H:i', strtotime($row->event_date));
            $imgSrc = $row->image ? htmlspecialchars($row->image) : './assets/images/loading.png';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?php echo $imgSrc; ?>" class="card-img-top" style="height:180px;object-fit:cover;"
                        onerror="this.src='./assets/images/loading.png'">
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($row->title); ?></h5>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-geo-alt-fill text-success"></i>
                            <?php echo htmlspecialchars($row->location); ?>
                        </p>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-clock text-success"></i> <?php echo $dateStr; ?>
                        </p>
                        <p class="text-secondary small flex-grow-1">
                            <?php echo nl2br(htmlspecialchars(substr($row->description, 0, 100))); ?>
                            <?php if (strlen($row->description) > 100)
                                echo '…'; ?>
                        </p>

                        <div class="mb-2">
                            <?php $pct = min(100, round($row->reg_count / max(1, $row->capacity) * 100)); ?>
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span><?php echo $row->reg_count; ?> / <?php echo $row->capacity; ?> seats</span>
                                <?php if ($isFull): ?>
                                    <span class="text-danger fw-semibold">Full</span>
                                <?php else: ?>
                                    <span class="text-success"><?php echo $row->capacity - $row->reg_count; ?> left</span>
                                <?php endif; ?>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar <?php echo $isFull ? 'bg-danger' : 'bg-success'; ?>"
                                    style="width:<?php echo $pct; ?>%"></div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-auto pt-2">
                            <a href="./?page=event/detail&id=<?php echo $row->id; ?>"
                                class="btn btn-outline-success btn-sm flex-grow-1">
                                Details <i class="bi bi-arrow-right"></i>
                            </a>
                            <?php if ($isAdmin): ?>
                                <a href="./?page=event/create&id=<?php echo $row->id; ?>" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus"></i>
                                </a>
                                <a href="./?page=event/edit&id=<?php echo $row->id; ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="./?page=event/delete&id=<?php echo $row->id; ?>" class="btn btn-sm btn-danger btn-del">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if ($count === 0): ?>
            <div class="col-12">
                <p class="text-muted text-center py-5">No events available yet. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($isAdmin): ?>
    <script>
        $(document).ready(function () {
            $('.btn-del').click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                Swal.fire({
                    title: 'Delete this event?',
                    text: 'All registrations for this event will also be deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((r) => {
                    if (r.isConfirmed) window.location.href = url;
                });
            });
        });
    </script>
<?php endif; ?>