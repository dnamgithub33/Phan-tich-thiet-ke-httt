<?php

session_start();

if (isset($_SESSION['member'])) {
    require_once "../../DAO/ContractDAO.php";
    require_once "../../DAO/MemberDAO.php";
    require_once "../../DAO/JobDAO.php";
    $member = @unserialize($_SESSION['member']);
    if ($member instanceof Member) {
        if ($member->role != "Laborer") {
            die();
        }
    }
    require_once "../base/validation.php";
    if (!isset($_GET['id'])||!validate_id($_GET['id'])) {
        header("location: list_contract.php");
        exit();
    }
    $contract = searchContract($_GET['id']);
    $manager = $contract->manager;
    $laborer = $contract->laborer;
    $jobs = $contract->job;
    if ($member instanceof Member && $laborer instanceof Laborer) {
        if ($member->id != $laborer->id) {
            die("Bạn không được truy cập hợp đồng này");
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
        <link href="https://cdn.datatables.net/v/bs5/dt-2.0.5/datatables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../base/css/main.css">
        <title>Home Page</title>
    </head>

    <body>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="\Datatables\datatables.js"></script>
        <script src="\Datatables\datatables.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>

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

                    <div class="col py-3">
                        <h1>Tạo hợp đồng mới</h1>

                        <hr style="border: 2px solid blue">
                        <br>
                        <div class="contract">
                            <div class="contract-body">
                                <p><strong>Ngày tạo:</strong> <?php
                                                                echo $contract->daycreate;
                                                                ?>
                                </p>
                                <p><strong>Giữa:</strong> Công ty ABC (Bên A), đại diện là ông/bà <strong><?php echo $manager->name; ?></strong></p>
                                <p><strong>Và:</strong> Ông/Bà <strong><?php echo $member->name; ?></strong> (Bên B)</p>

                                <h3>Điều 1: Nội dung hợp đồng</h3>
                                <p>Bên A thuê Bên B làm công việc
                                    <strong>
                                        
                                        <?php 
                                        foreach($jobs as $job){
                                            echo $job->name;
                                        } ?>
                                    </strong>
                                    tại <span><?php echo $job->company; ?></span>
                                </p>


                                <h3>Điều 2: Thời gian hợp đồng</h3>
                                <p class="d-inline">Hợp đồng có hiệu lực từ ngày ký hợp đồng với hạn hợp đồng 
                                    <span><?php echo $contract->term; ?></span> năm
                                </p>


                                <h3>Điều 3: Mức lương</h3>
                                <p>Bên A trả cho Bên B mức lương hàng tháng là <strong><span><?php foreach($jobs as $job){echo $job->basesalary; }?></span> VND</strong>.</p>

                                <h3>Điều 4: Quyền lợi và nghĩa vụ của các bên</h3>
                                <ul>
                                    <li>Bên A cung cấp đầy đủ trang thiết bị làm việc cho Bên B.</li>
                                    <li>Bên B thực hiện công việc theo sự phân công của Bên A.</li>
                                </ul>

                                <h3>Điều 5: Chấm dứt hợp đồng</h3>
                                <p>Hợp đồng có thể chấm dứt trước thời gian quy định nếu có sự thỏa thuận giữa các bên hoặc vi phạm các điều khoản trong hợp đồng.</p>

                                <h3>Điều 6: Cam kết</h3>
                                <p>Các bên cam kết thực hiện đúng và đầy đủ các điều khoản trong hợp đồng này.</p>
                            </div>

                            <div class="contract-footer">
                                <p>Đại diện Bên A: <strong><?php echo $manager->name; ?></strong></p>
                                <p>Đại diện Bên B: <strong><?php echo $laborer->name; ?></strong></p>
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