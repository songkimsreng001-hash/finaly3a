<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold m-0">Users List <i class="bi bi-people"></i></h3>

        <a href="./?page=user/create" role="button" class="btn btn-info">
            <i class="bi bi-person-plus-fill"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Options</th>
            </tr>

            <?php
            $users = getUsers();
            $count = 1;
            while ($row = $users->fetch_object()) {
                echo '<tr>
                        <td>' . $count . '</td>
                        <td>
                            <img src="' . ($row->photo ?? './assets/images/Profile_PNG.png') . '" 
                            class="rounded img-thumbnail" style="max-width:100px"/>
                        </td>
                        <td>' . $row->name . '</td>
                        <td>
                            <a  href="./?page=user/update&id=' . $row->id . '" 
                            role="button" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="./?page=user/delete&id=' . $row->id . '" 
                            role="button" class="btn btn-danger">
                            <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>';
                $count++;
            }
            ?>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.btn-danger').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = $(this).attr('href');
                }
                });
            });
        });
</script>