<?php
$id = $_GET['id'];
$targetUser = readUser($id);

if ($targetUser == null || $targetUser->level == 'admin') {
    header('Location: ./?page=user/list');
    exit;
}

$nameErr = $usernameErr = $passwdErr = '';
$name = $targetUser->name;
$username = $targetUser->username;

$successMsg = '';
$errorMsg = '';

if (isset($_POST['name'], $_POST['username'], $_POST['passwd'])) {

    $photo = $_FILES['photo'];
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);

    if (empty($name)) {
        $nameErr = 'Please input name!';
    }

    if (empty($username)) {
        $usernameErr = 'Please input username!';
    }

    if ($targetUser->username !== $username && usernameExists($username)) {
        $usernameErr = 'Please choose another username!';
    }

    if (empty($nameErr) && empty($usernameErr)) {

        try {
            if (updateUser($id, $name, $username, $passwd, $photo)) {

                $successMsg = 'User updated successfully!';
                $targetUser = readUser($id); // refresh data

            } else {
                $errorMsg = 'Update failed!';
            }

        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
    } else {
        $errorMsg = $nameErr . '<br>' . $usernameErr;
    }
}
?>
<form method="post" action="./?page=user/update&id=<?php echo $id; ?>"
      enctype="multipart/form-data"
      class="col-md-10 col-lg-6 mx-auto">

    <h3 class="fw-bold text-center mb-3">
        <i class="bi bi-pencil-square"></i> Update User
    </h3>
    <div class="d-flex justify-content-center mb-3">
        <input name="photo" type="file" id="profileUpload" hidden>

        <label role="button" for="profileUpload">
            <img id="preview"
                 src="<?php echo $targetUser->photo ?: './assets/images/Profile_PNG.png'; ?>"
                 class="rounded img-thumbnail"
                 style="max-width:200px">
        </label>
    </div>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" value="<?php echo htmlspecialchars($name) ?>"
               type="text"
               class="form-control <?php echo empty($nameErr) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback"><?php echo $nameErr ?></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" value="<?php echo htmlspecialchars($username) ?>"
               type="text"
               class="form-control <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback"><?php echo $usernameErr ?></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="passwd" type="password" class="form-control">
        <small class="text-muted">Leave blank if you don’t want to change</small>
    </div>

    <button type="submit" class="btn btn-outline-primary px-4">
        <i class="bi bi-save"></i> Update
    </button>

</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

<?php if (!empty($successMsg)): ?>
Swal.fire({
    icon: 'success',
    title: 'Success',
    html: `
        <i class="bi bi-check-circle-fill"></i><br>
        Your updating was successfully!<br><br>
        <a href="./?page=user/list" class="btn btn-success">
            <i class="bi bi-list"></i> Go to List
        </a>
    `,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
Swal.fire({
    icon: 'error',
    title: 'Error',
    html: '<?php echo addslashes($errorMsg); ?>'
});
<?php endif; ?>
document.getElementById('profileUpload').addEventListener('change', function () {
    const [file] = this.files;
    if (file) {
        document.getElementById('preview').src = URL.createObjectURL(file);
    }
});
</script>