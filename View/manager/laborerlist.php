<?php

session_start();

if (isset($_SESSION['member'])) {
    $member = @unserialize($_SESSION['member']);
    if ($member instanceof Member) {
        if ($member->role != "Manager") {
            die();
        }
    }
    require_once "../../DAO/MemberDAO.php";
    
    if (isset($_GET['search'])&&validation($_GET['search'])) {
        $laborerlist = searchLaborer($_GET['search'],1);
    } else {
        $laborerlist = searchLaborer('',1);
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
                    $fileContent = file_get_contents("../base/baseUI.html");
                    $member = unserialize($_SESSION['member']);
                    $name = $member->name;
                    $update = str_replace("[user]", $name, $fileContent);
                    echo $update;
                ?>

                    <div class="col py-3">
                        <h1>Danh sách người lao động</h1>

                        <hr style="border: 2px solid blue">
                        <br>

                        <div class="card">
                            <div class="card-body">
                                <form class="mb-5" method="GET">
                                    <div class="row justify-content-center align-items-center g-2">
                                        <div class="col">
                                            <input type="text" name="search" class="form-control"
                                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                                placeholder="Tìm kiếm theo tên">
                                        </div>
                                        <div class="col"> <button type="submit" class="btn btn-secondary">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </form>
                                <table id="staff_table" class="table table-striped table-hover">
                                    <thead style="position: sticky; top: 0; ">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Tên</th>
                                            <th scope="col">Ngày Sinh</th>
                                            <th scope="col">Giới Tính</th>
                                            <th scope="col">Số Điện Thoại</th>
                                            <th scope="col">Địa Chỉ</th>
                                            <th scope="col">Số CCCD</th>
                                            <th scope="col">Ghi chú</th>
                                            <th scope="col">Thao Tác</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                    
                                        foreach($laborerlist as $laborer) {
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $laborer->id; ?></th>
                                                <td><?php echo $laborer->name; ?></td>
                                                <td><?php echo $laborer->dateOfBirth; ?></td>
                                                <td><?php echo $laborer->gender; ?></td>
                                                <td><?php echo $laborer->phone; ?></td>
                                                <td><?php echo $laborer->address; ?></td>
                                                <td><?php echo $laborer->citizenIdentification; ?></td>
                                                <td><?php echo $laborer->note; ?></td>
                                                <td>
                                                    <a href="editlaborer.php?id=<?php echo $laborer->id; ?>"
                                                        class="btn btn-success px-4 ">Sửa</a>
                                                    <a onclick="return confirm('Bạn có chắc muốn xoá người lao động này không?');"
                                                        href="deletelaborer.php?id=<?php echo $laborer->id; ?>"
                                                        class="btn btn-danger px-4">Xoá</a>
                                                </td>
                                            </tr>
                                            <?php
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