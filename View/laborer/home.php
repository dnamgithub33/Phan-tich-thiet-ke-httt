<?php

session_start();
if (isset($_SESSION['member'])) {
    require_once "../../Model/Member.php";
    $member = @unserialize($_SESSION['member']);
    if ($member instanceof Member) {
        if ($member->role != "Laborer") {
            die();
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../base/css/main.css">
        <title>Home Page</title>
    </head>

    <body>
        <!-- Side Bar -->
        <div class="side_bar">
            <div class="container-fluid">
                <div class="row flex-nowrap">
                    <?php
                    $fileContent = file_get_contents("../base/user_baseUI.html");
                    $member = unserialize($_SESSION['member']);
                    $name = $member->name;
                    $update = str_replace("[user]", $name, $fileContent);
                    echo $update;
                    ?>

                    <div class="col py-3 main">
                        <h1>Trang chủ</h1>

                        <hr style="border: 2px solid blue">
                        <br><br>

                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-between">
                                    <div class="col-xl-6">
                                        <div class="card" style="background-color: #0a0a0a; color: #fff">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2>Danh sách hợp đồng</h2>
                                                    </div>
                                                    <div class="col" style="max-width: fit-content !important;">
                                                        <i class="fs-4 bi bi-table"></i>
                                                    </div>
                                                </div>
                                                <br><br>
                                                <a href="list_contract.php" class="text-white text-decoration-none"
                                                    style="min-width: 100%; text-align: center;">
                                                    Xem Thêm
                                                    <i class="bi bi-arrow-right-circle-fill mx-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: ../login.php");
    exit();
}
?>