<?php
$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr = '';
$successMsg = $errorMsg = '';

if (isset($_POST['changePasswd'], $_POST['oldPasswd'], $_POST['newPasswd'], $_POST['confirmNewPasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd']);
    $newPasswd = trim($_POST['newPasswd']);
    $confirmNewPasswd = trim($_POST['confirmNewPasswd']);

    if (empty($oldPasswd)) {
        $oldPasswdErr = 'Please input your old password';
    }

    if (empty($newPasswd)) {
        $newPasswdErr = 'Please input your new password';
    }

    if ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'Password does not match';
    } else {
        if (!isUserHasPassword($oldPasswd)) {
            $oldPasswdErr = 'Password is incorrect';
        }
    }

    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassowrd($newPasswd)) {
            unset($_SESSION['user_id']);
            $successMsg = 'Password changed successfully!';
        } else {
            $errorMsg = 'Try again.';
        }
    }
}
if (isset($_POST['uploadPhoto']) && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];

    if (empty($photo['name'])) {
        $errorMsg = 'Please select a photo.';
    } else {
        try {
            if (changeProfileImage($photo)) {
                $successMsg = 'Profile image updated!';
            } else {
                $errorMsg = 'Upload failed.';
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
    }
}
if (isset($_POST['deletePhoto'])) {
    deleteProfileImage();
    $successMsg = 'Profile image deleted!';
}
?>

<style>
.profile-img {
    width: 200px;
    height: 200px;
    object-fit: cover;
}
</style>

<div class="row mt-4">
    <div class="col-6">
        <form method="post" action="./?page=profile" enctype="multipart/form-data">

            <div class="text-center mb-3">
                <input name="photo" type="file" id="profileUpload" hidden accept="image/*">

                <label for="profileUpload" role="button">
                    <img id="imgPreview"
                         src="<?php echo loggedInUser()->photo ?? './assets/images/Profile_PNG.png' ?>"
                         class="rounded-circle img-thumbnail profile-img">
                </label>
            </div>

            <div class="text-center">
                <button type="button" onclick="confirmDelete()" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>

                <button type="submit" name="uploadPhoto" class="btn btn-success">
                    <i class="bi bi-upload"></i> Upload
                </button>
            </div>

            <!-- Hidden submit for delete -->
            <button type="submit" name="deletePhoto" id="deleteBtn" hidden></button>

        </form>
    </div>
    <div class="col-6">
        <form method="post" action="./?page=profile" class="col-md-8 mx-auto">

            <h3><i class="bi bi-lock"></i> Change Password</h3>

            <div class="mb-3">
                <label class="form-label">Old Password</label>
                <input value="<?php echo $oldPasswd ?>"
                       name="oldPasswd"
                       type="password"
                       class="form-control <?php echo empty($oldPasswdErr) ? '' : 'is-invalid' ?>">

                <div class="invalid-feedback"><?php echo $oldPasswdErr ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input name="newPasswd"
                       type="password"
                       class="form-control <?php echo empty($newPasswdErr) ? '' : 'is-invalid' ?>">

                <div class="invalid-feedback"><?php echo $newPasswdErr ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input name="confirmNewPasswd" type="password" class="form-control">
            </div>

            <button type="submit" name="changePasswd" class="btn btn-outline-primary">
                <i class="bi bi-arrow-repeat"></i> Change
            </button>

        </form>
    </div>

</div>
<script>
<?php if ($successMsg): ?>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?php echo $successMsg ?>',
});
<?php endif; ?>

<?php if ($errorMsg): ?>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?php echo $errorMsg ?>',
});
<?php endif; ?>
document.getElementById('profileUpload').addEventListener('change', function () {
    const [file] = this.files;
    if (file) {
        document.getElementById('imgPreview').src = URL.createObjectURL(file);
    }
});

function confirmDelete() {
    Swal.fire({
        title: 'Are you sure?',
        text: "Delete your profile image?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteBtn').click();
        }
    });
}
</script>