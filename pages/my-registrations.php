<?php
?>
<div class="container mt-4">
    <h3 class="fw-bold mb-4">My Registrations <i class="bi bi-list-check"></i></h3>

    <?php
    $regs  = getMyRegistrations($user->id);
    $count = 0;
    ?>

    <div class="row g-3">
    <?php while ($row = $regs->fetch_object()):
        $count++;
        $badgeClass = match($row->status) {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default    => 'bg-warning text-dark',
        };
        $imgSrc = $row->image ? htmlspecialchars($row->image) : './assets/images/loading.png';
    ?>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="row g-0 h-100">
                    <div class="col-4">
                        <img src="<?php echo $imgSrc ?>"
                             class="img-fluid rounded-start h-100"
                             style="object-fit:cover;min-height:120px;"
                             onerror="this.src='./assets/images/Profile_PNG.png'">
                    </div>
                    <div class="col-8">
                        <div class="card-body py-2 px-3 d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row->title) ?></h6>
                                <span class="badge <?php echo $badgeClass ?> ms-1">
                                    <?php echo ucfirst($row->status) ?>
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row->location) ?>
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar3"></i>
                                <?php echo date('d M Y · H:i', strtotime($row->event_date)) ?>
                            </p>
                            <?php if (!empty($row->note)): ?>
                                <p class="text-muted small mb-1 fst-italic">
                                    "<?php echo htmlspecialchars($row->note) ?>"
                                </p>
                            <?php endif; ?>
                            <div class="mt-auto d-flex gap-2">
                                <a href="./?page=event/detail&id=<?php echo $row->event_id ?>"
                                   class="btn btn-sm btn-outline-success">View</a>
                                <?php if ($row->status === 'pending'): ?>
                                    <a href="./?page=event/cancel&event_id=<?php echo $row->event_id ?>"
                                       class="btn btn-sm btn-outline-danger btn-cancel">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <?php if ($count === 0): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-calendar-x fs-1 text-muted"></i>
            <p class="text-muted mt-2">You have not registered for any events yet.</p>
            <a href="./?page=events" class="btn btn-success">Browse Events</a>
        </div>
    <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.btn-cancel').click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title:'Cancel this registration?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            confirmButtonText:'Yes, cancel it!'
        }).then((r)=>{ if(r.isConfirmed) window.location.href = url; });
    });
});
</script>