<?php

$id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    header('Location: ./?page=events');
    exit;
}

$isFull       = $event->reg_count >= $event->capacity;
$isRegistered = $user ? isUserRegistered($event->id, $user->id) : false;
$imgSrc       = $event->image ? htmlspecialchars($event->image) : './assets/images/loading.png';
$pct          = min(100, round($event->reg_count / max(1, $event->capacity) * 100));
?>
<div class="container mt-4">
    <a href="./?page=events" class="btn btn-sm btn-outline-warning mb-3">
        <i class="bi bi-arrow-left"></i> Back to Events
    </a>

    <div class="row g-4">
        <div class="col-md-5">
            <img src="<?php echo $imgSrc ?>"
                 class="img-fluid rounded shadow-sm w-100"
                 style="max-height:300px;object-fit:cover;"
                 onerror="this.src='./assets/images/loading.png'">

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <p class="mb-2">
                        <i class="bi bi-geo-alt-fill text-success"></i>
                        <strong>Location:</strong> <?php echo htmlspecialchars($event->location) ?>
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-calendar3 text-success"></i>
                        <strong>Date:</strong> <?php echo date('l, d M Y', strtotime($event->event_date)) ?>
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-clock text-success"></i>
                        <strong>Time:</strong> <?php echo date('H:i', strtotime($event->event_date)) ?>
                    </p>
                    <p class="mb-3">
                        <i class="bi bi-people text-success"></i>
                        <strong>Capacity:</strong> <?php echo $event->reg_count ?> / <?php echo $event->capacity ?> registered
                    </p>
                    <div class="progress mb-3" style="height:8px;">
                        <div class="progress-bar <?php echo $isFull ? 'bg-danger' : 'bg-success' ?>"
                             style="width:<?php echo $pct ?>%"></div>
                    </div>

                    <?php if ($isAdmin): ?>
                        <div class="d-flex gap-2">
                            <a href="./?page=event/edit&id=<?php echo $event->id ?>"
                               class="btn btn-primary btn-sm flex-grow-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="./?page=event/registrations&id=<?php echo $event->id ?>"
                               class="btn btn-info btn-sm flex-grow-1">
                                <i class="bi bi-list-check"></i> Registrations
                            </a>
                        </div>
                    <?php elseif ($user): ?>
                        <?php if ($isRegistered): ?>
                            <a href="./?page=event/cancel&event_id=<?php echo $event->id ?>"
                               class="btn btn-outline-danger w-100" id="btn-cancel">
                                <i class="bi bi-x-circle"></i> Cancel My Registration
                            </a>
                        <?php elseif ($isFull): ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-slash-circle"></i> Event Full
                            </button>
                        <?php else: ?>
                            <a href="./?page=event/register&id=<?php echo $event->id ?>"
                               class="btn btn-success w-100">
                                <i class="bi bi-calendar-check"></i> Register Now
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="./?page=login" class="btn btn-success w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login to Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <h2 class="fw-bold"><?php echo htmlspecialchars($event->title) ?></h2>
            <?php if ($isRegistered): ?>
                <div class="alert alert-success py-2">
                    <i class="bi bi-check-circle-fill"></i> You are registered for this event.
                </div>
            <?php endif; ?>
            <hr>
            <p class="text-secondary" style="white-space:pre-line;line-height:1.7;">
                <?php echo htmlspecialchars($event->description) ?>
            </p>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#btn-cancel').click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title:'Cancel registration?',
            text:'Your spot will be released.',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            confirmButtonText:'Yes, cancel it!'
        }).then((r)=>{ if(r.isConfirmed) window.location.href = url; });
    });
});
</script>