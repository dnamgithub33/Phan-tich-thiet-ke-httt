<?php

session_start();

if (isset($_SESSION['member'])) {
    $member = @unserialize($_SESSION['member']);
    if ($member instanceof Member) {
        if ($member->role != "Manager") {
            die();
        }
    }
    require_once "../../DAO/ContractDAO.php";
    require_once "../../DAO/MemberDAO.php";
    require_once "../base/validation.php";

    if (!isset($_GET['id'])||!validate_id($_GET['id'])) {
        header("location: laborerlist_contract.php");
        exit();
    }
    
    $laborer = searchLaborer($_GET['id'],2);
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
                    $fileContent = file_get_contents("../base/baseUI.html");
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
                                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                                    echo date('d/m/Y');
                                    ?>
                                </p>
                                <p><strong>Giữa:</strong> Công ty ABC (Bên A), đại diện là ông/bà <strong><?php echo $name;?></strong></p>
                                <p><strong>Và:</strong> Ông/Bà <strong><?php echo $laborer->name; ?></strong> (Bên B)</p>

                                <h3>Điều 1: Nội dung hợp đồng</h3>
                                <p>Bên A thuê Bên B làm công việc <strong>_________</strong> tại _________</p>

                                <h3>Điều 2: Thời gian hợp đồng</h3>
                                <p>Hợp đồng có hiệu lực từ ngày ký hợp đồng với hạn hợp đồng _________ năm</p>

                                <h3>Điều 3: Mức lương</h3>
                                <p>Bên A trả cho Bên B mức lương hàng tháng là <strong>_________</strong>.</p>

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
                                <p>Đại diện Bên A: <strong><?php echo $name;?></strong></p>
                                <p>Đại diện Bên B: <strong>Chữ ký</strong></p>
                            </div>
                            <form action="../../DAO/ContractDAO.php" method="POST" onclick="return confirm('Bạn có chắc muốn tạo hợp đồng với người lao động <?php echo $laborer->name; ?>không?');">
                                <input type="hidden" name="manager_id" value="<?php echo $member->id; ?>">
                                <input type="hidden" name="laborer_id" value="<?php echo $laborer->id; ?>">
                                <button type="submit" class="btn btn-primary">Tạo hợp đồng</button>
                            </form>

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