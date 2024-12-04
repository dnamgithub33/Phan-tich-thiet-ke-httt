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
    if (!isset($_GET['id']) || !validate_id($_GET['id'])) {
        header("location: list_contract.php");
        exit();
    }
    $contract = searchContract($_GET['id']);
    $manager = $contract->manager;
    $laborer = $contract->laborer;
    if ($member instanceof Member && $laborer instanceof Laborer) {
        if ($member->id != $laborer->id) {
            die("Bạn không được truy cập hợp đồng này");
        }
    }
    $jobs = getListJob();
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
                            <form action="../../DAO/ContractDAO.php" method="POST">
                                <div class="contract-body">
                                    <p><strong>Ngày tạo:</strong> <?php
                                                                    echo $contract->daycreate;
                                                                    ?>
                                    </p>
                                    <p><strong>Giữa:</strong> Công ty ABC (Bên A), đại diện là ông/bà <strong><?php echo $manager->name; ?></strong></p>
                                    <p><strong>Và:</strong> Ông/Bà <strong><?php echo $member->name; ?></strong> (Bên B)</p>

                                    <h3>Điều 1: Nội dung hợp đồng</h3>
                                    <p>Bên A thuê Bên B các làm công việc
                                        <strong>
                                            <select name="job_id_1" class="form-select w-auto" id="jobSelect1" onchange="updateLocation()">
                                                <option value="">Chọn công việc</option>
                                                <?php foreach ($jobs as $job): ?>
                                                    <option value="<?php echo $job->id; ?>" data-salary1="<?php echo $job->basesalary; ?>" data-location1="<?php echo $job->company; ?>"><?php echo $job->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </strong>
                                        tại <span id="locationText">______</span>
                                        <strong>
                                            <select name="job_id_2" class="form-select w-auto" id="jobSelect2" onchange="updateLocation()">
                                                <option value="">Chọn công việc</option>
                                                <?php foreach ($jobs as $job): ?>
                                                    <option value="<?php echo $job->id; ?>" data-salary2="<?php echo $job->basesalary; ?>" data-location2="<?php echo $job->company; ?>"><?php echo $job->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </strong>
                                        tại <span id="locationText2">______</span>
                                    </p>


                                    <h3>Điều 2: Thời gian hợp đồng</h3>
                                    <p class="d-inline">Hợp đồng có hiệu lực từ ngày ký hợp đồng với hạn hợp đồng
                                        <input type="number" name="term" class="form-control w-auto d-inline" placeholder="Số năm" min="1" required> năm
                                    </p>


                                    <h3>Điều 3: Mức lương</h3>
                                    <p>Bên A trả cho Bên B mức lương cho công việc 1 là <strong><span id="salary1">______</span> VND</strong>.</p>
                                    <p>Bên A trả cho Bên B mức lương cho công việc 2 là <strong><span id="salary2">______</span> VND</strong>.</p>

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
                                    <p>Đại diện Bên B: <strong>Chữ ký</strong></p>
                                </div>

                                <input type="hidden" name="contract_id" value="<?php echo $contract->id; ?>">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn ký hợp đồng này không?');">Ký hợp đồng</button>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function updateLocation() {
                // Lấy giá trị của jobSelect1 và jobSelect2
                var select1 = document.getElementById("jobSelect1");
                var select2 = document.getElementById("jobSelect2");

                // Cập nhật thông tin cho jobSelect1
                var selectedOption1 = select1.options[select1.selectedIndex];
                var location1 = selectedOption1.getAttribute("data-location1");
                var salary1 = selectedOption1.getAttribute("data-salary1");
                if (location1) {
                    document.getElementById("locationText").textContent = location1;
                    document.getElementById("salary1").textContent = salary1;
                } else {
                    document.getElementById("locationText").textContent = "______";
                    document.getElementById("salary1").textContent = "______";
                }

                // Cập nhật thông tin cho jobSelect2
                var selectedOption2 = select2.options[select2.selectedIndex];
                var location2 = selectedOption2.getAttribute("data-location2");
                var salary2 = selectedOption2.getAttribute("data-salary2");
                if (location2) {
                    document.getElementById("locationText2").textContent = location2;
                    document.getElementById("salary2").textContent = salary2;
                } else {
                    document.getElementById("locationText2").textContent = "______";
                    document.getElementById("salary2").textContent = "______";
                }

                // Lọc jobSelect2 để loại bỏ công việc đã chọn trong jobSelect1
                for (var i = 0; i < select2.options.length; i++) {
                    var option = select2.options[i];
                    if (option.value === select1.value || select1.value === "") {
                        option.disabled = false; // Bật lại các option nếu jobSelect1 không có lựa chọn
                    } else {
                        option.disabled = false;
                    }
                }
            }
        </script>

    </body>

    </html>
<?php
} else {
    header("Location: ../login.php");
    exit();
}
?>