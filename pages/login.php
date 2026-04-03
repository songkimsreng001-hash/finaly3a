
<?php
$username = '';
$usernameErr = $passwdErr = '';
if (isset($_POST['username'], $_POST['passwd'])) {
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);
    if (empty($username)) {
        $usernameErr = 'please input username!';
    }
    if (empty($passwd)) {
        $passwdErr = 'please input password!';
    }
    if (empty($usernameErr) && empty($passwdErr)) {
        $user = logUserIn($username,$passwd);
        if($user !== false){
            $_SESSION['user_id'] = $user->id;
            header('Location: ./?page=dashboard');
        }else{
            $usernameErr = 'Username or password is incorrect!';
        }
    }
}
?>

<form method="post" action="./?page=login" class="col-md-10 col-lg-6 mx-auto mt-4">
    <h3 class="text-center fw-bold">Login</h3>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <input name="username"
                   value="<?php echo $username ?>"
                   type="text"
                   class="form-control <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback">
                <?php echo $usernameErr ?>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input name="passwd"
                   type="password"
                   class="form-control <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
            <div class="invalid-feedback">
                <?php echo $passwdErr ?>
            </div>
        </div>
    </div>
    <!-- <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Remember me</label> -->
    </div>
        <button class="btn btn-primary" type="submit">Login <i class="bi bi-box-arrow-in-right"></i> </button>
    </div>

</form>
