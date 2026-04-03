<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container" >
        <a class="navbar-brand" style="font-weight: bold; color: white;" href="<?php echo $baseUrl ?>"><i class="bi bi-house-fill"> Home</i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <!-- <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li> -->
                <?php if ($isAdmin) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $baseUrl ?>?page=user/list" style="font-weight: bold; color: white;">Users <i class="bi bi-people"></i></a>
                    </li>
                <?php } ?>
                <li class="nav-item dropdown" style="font-weight: bold; color: white;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false" >
                        <i class="bi bi-menu-button-wide" style="color: white;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (empty($user)) { ?>
                            <li><a class="dropdown-item" style="font-weight: bold; color: darkblue;" 
                                href="<?php echo $baseUrl ?>?page=login">Login <i class="bi bi-box-arrow-in-right"></i></a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" style="font-weight: bold; color: darkblue;" 
                                href="<?php echo $baseUrl ?>?page=register">Register <i class="bi bi-person-fill-add"></i></a></li>
                        <?php } else { ?>
                            <li><a class="dropdown-item" style="font-weight: bold; color: darkblue;" 
                                href="<?php echo $baseUrl ?>?page=profile">Profile <i class="bi bi-person-lines-fill"></i></a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" style="font-weight: bold; color: darkblue;" 
                                href="<?php echo $baseUrl ?>?page=logout">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li> -->
            </ul>
        </div>
    </div>
</nav>