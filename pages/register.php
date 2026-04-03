<?php
$nameErr = $usernameErr = $passwdErr = '';
$name = $username = '';
$successMsg = $errorMsg = '';

if (isset($_POST['name'], $_POST['username'], $_POST['passwd'], $_POST['confirmPasswd'])) {

    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);
    $confirmPasswd = trim($_POST['confirmPasswd']);

    $photo = $_FILES['photo'] ?? null;
    if (empty($name)) {
        $nameErr = 'Please input name!';
    }

    if (empty($username)) {
        $usernameErr = 'Please input username!';
    }

    if (empty($passwd)) {
        $passwdErr = 'Please input password!';
    }

    if ($passwd !== $confirmPasswd) {
        $passwdErr = 'Password does not match!';
    }

    if (usernameExists($username)) {
        $usernameErr = 'Username already exists!';
    }
    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {

        if (registerUser($name, $username, $passwd)) {

            if (!empty($photo['name'])) {
                try {
                    changeProfileImage($photo);
                } catch (Exception $e) {
                    $errorMsg = 'Registration successful, but photo upload failed: ' . $e->getMessage();
                }
            }

            $successMsg = 'Registration successful!';
            $name = $username = '';

        } else {
            $errorMsg = 'Registration failed!';
        }

    } else {
        $errorMsg = $nameErr . '<br>' . $usernameErr . '<br>' . $passwdErr;
    }
}
?>

<style>
.profile-preview {
    width: 150px;
    height: 150px;
    object-fit: cover;
}
</style>

<form method="post" action="./?page=register" enctype="multipart/form-data"
      class="col-md-10 col-lg-6 mx-auto mt-4">

    <h3 class="text-center fw-bold mb-3">
        <i class="bi bi-person-plus"></i> Register
    </h3>
    <div class="text-center mb-3">
        <input type="file" name="photo" id="photoUpload" hidden accept="image/*">

        <label for="photoUpload" role="button">
            <img id="preview"
                 src="./assets/images/Profile_PNG.png"
                 class="rounded-circle img-thumbnail profile-preview">
        </label>
    </div>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input name="name" value="<?php echo htmlspecialchars($name) ?>" type="text"
                   class="form-control <?php echo empty($nameErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback"><?php echo $nameErr ?></div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-at"></i></span>
            <input name="username" value="<?php echo htmlspecialchars($username) ?>" type="text"
                   class="form-control <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback"><?php echo $usernameErr ?></div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input name="passwd" type="password"
                   class="form-control <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback"><?php echo $passwdErr ?></div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input name="confirmPasswd" type="password" class="form-control">
        </div>
    </div>

    <button type="submit" class="btn btn-outline-primary px-4">
        <i class="bi bi-check-lg"></i> Register
    </button>

</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

<?php if (!empty($successMsg)): ?>
Swal.fire({
    icon: 'success',
    title: 'Registration Successful!',
    html: `
        Your account has been created successfully 🎉<br><br>
        <a href="./?page=login" class="btn btn-success">
            Go to Login
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

// IMAGE PREVIEW
document.getElementById('photoUpload').addEventListener('change', function () {
    const [file] = this.files;
    if (file) {
        document.getElementById('preview').src = URL.createObjectURL(file);
    }
});
</script>