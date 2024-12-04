<?php

session_start();

if (isset($_SESSION['member'])) {
    require_once "../../DAO/ContractDAO.php";
    require_once '../../Model/Contract.php';
    $member = @unserialize($_SESSION['member']);
    $listcontract = getListContract($member->id);
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
                        <h1>Hợp đồng</h1>

                        <hr style="border: 2px solid blue">
                        <br>

                        <div class="card">
                            <div class="card-body">
                                <table id="staff_table" class="table table-striped table-hover">
                                    <thead style="position: sticky; top: 0; ">
                                        <tr>
                                            <th scope="col">Id hợp đồng</th>
                                            <th scope="col">Tên người lao động</th>
                                            <th scope="col">Tên quản lý</th>
                                            <th scope="col">Công việc</th>
                                            <th scope="col">Công ty</th>
                                            <th scope="col">Lương cơ bản</th>
                                            <th scope="col">Ngày tạo</th>
                                            <th scope="col">Ngày ký</th>
                                            <th scope="col">Hạn hợp đồng</th>
                                            <th scope="col">Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                    
                                        foreach($listcontract as $contract) {
                                            if($contract instanceof Contract){
                                                $laborer = $contract->laborer;
                                                $manager = $contract->manager;
                                                $jobs =[];
                                                $jobs = $contract->job;
                                            }
                                            
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $contract->id; ?></th>
                                                <td><?php echo $laborer->name; ?></td>
                                                <td><?php echo $manager->name; ?></td>
                                                <td><?php if(!empty($contract->daysign)) foreach($jobs as $job) echo $job->name ." "; ?></td>
                                                <td><?php if(!empty($contract->daysign)) foreach($jobs as $job) echo $job->company." "; ?></td>
                                                <td><?php if(!empty($contract->daysign)) foreach($jobs as $job) echo $job->basesalary." "; ?></td>
                                                <td><?php echo $contract->daycreate; ?></td>
                                                <td><?php echo $contract->daysign; ?></td>
                                                <td><?php if($contract->term) echo $contract->term . " năm"; ?> </td>
                                                <?php 
                                                    if($contract->daysign){ 
                                                ?>
                                                    <td><a href="viewcontract.php?id=<?php echo $contract->id; ?>" class="btn btn-primary px-4 w-100">Xem</a></td>                                                    
                                                <?php 
                                                    } else { 
                                                ?>

                                                    <td><a href="signcontract.php?id=<?php echo $contract->id; ?>" class="btn btn-success px-4 w-100">Ký</a></td>
                                                <?php 
                                                    } 
                                                ?>
                                            </tr>
                                            <?php
                                            $jobs=[];
                                        }
                                        ?>

                                    </tbody>
                                    <script>
                                        $(document).ready(function () {
                                            new DataTable('#staff_table', {
                                                language: {
                                                    info: 'Trang _PAGE_/_PAGES_',
                                                    infoEmpty: 'Không có dữ liệu',
                                                    infoFiltered: '(Lọc từ _MAX_ item)',
                                                    lengthMenu: 'Hiển thị _MENU_ item / trang',
                                                    zeroRecords: 'Không có item tương ứng',
                                                    search: 'Tìm kiếm'
                                                }
                                            });
                                        });
                                    </script>

                                </table>
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