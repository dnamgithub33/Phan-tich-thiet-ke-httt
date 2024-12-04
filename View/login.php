<?php
    session_start();
    // var_dump(unserialize($_SESSION['member']));
    require_once "../Model/Member.php";
    if(isset($_SESSION['member']))
    {
        $member = @unserialize($_SESSION['member']);
        if ($member instanceof Member) {
            if ($member->role === "Manager") {
                header("Location: manager/home.php");
                exit();
            } else {
                header("Location: laborer/home.php");
                exit();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/css/main.css" />
    <title>Login Page</title>
</head>

<body>
    <div class="login_form">
        <section class="vh-100">
            <div class="container h-custom">
                <div class="center row d-flex justify-content-center align-items-center h-100">
                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <p class="h1">Đăng nhập</p>

                        <?php
                        if (isset($_GET['error'])) { ?>
                            <p class="error text-danger">*<?php echo $_GET['error']; ?></p>
                            <?php
                        } ?>

                        <form method="post" action="../DAO/MemberDAO.php">
                            <!-- Username input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="form3Example3">Tài khoản</label>
                                <input type="text" name="username" id="form3Example3"
                                    class="form-control form-control-lg" />
                            </div>

                            <!-- Password input -->
                            <div data-mdb-input-init class="form-outline mb-3">
                                <label class="form-label" for="form3Example4">Mật khẩu</label>
                                <input type="password" name="password" id="form3Example4"
                                    class="form-control form-control-lg" />
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Checkbox -->
                                <div class="form-check mb-0">
                                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                                    <label class="form-check-label" for="form2Example3">
                                        Ghi nhớ tài khoản
                                    </label>
                                </div>
                                <a href="forget_password.php" class="text-body">Quên mật khẩu?</a>
                            </div>

                            <div class="text-center text-lg-start mt-4 pt-2">
                                <button type="submit" name="submit" data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem">
                                    Đăng nhập
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>