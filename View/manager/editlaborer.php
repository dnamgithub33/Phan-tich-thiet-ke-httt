<?php

    session_start();
    
    if (isset($_SESSION['member'])) {
        require_once "../../DAO/MemberDAO.php";
        $member = @unserialize($_SESSION['member']);
        if ($member instanceof Member) {
            if ($member->role != "Manager") {
                die();
            }
        }
        require_once "../base/validation.php";
        if (isset($_GET['id'])&&validate_id($_GET['id'])){
            $laborer = searchLaborer($_GET['id'],2);
            $laborer = @unserialize($_SESSION['laboreredit']);
        } else {
            header("Location: laborerlist.php");
            exit();
        }
        if(isset($_GET['err'])){
            if($_GET['err']=="cccd"){
                echo "<script>alert('Số căn cước công dân đã tồn tại')</script>";
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
                    $fileContent = file_get_contents("../base/baseUI.html");
                    $member = unserialize($_SESSION['member']);
                    $name = $member->name;
                    $update = str_replace("[user]", $name, $fileContent);
                    echo $update;
                ?>

                        <div class="col py-3">
                            <h1>Sửa thông tin người lao động</h1>

                            <hr style="border: 2px solid blue">
                            <br><br>

                            <div class="card card-registration">

                                <div class="card-body">

                                <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Nhập thông tin</h3>
                                    <form method="post" action="../../DAO/MemberDAO.php">

                                        <div class="row">
                                            <div class="col-md-6 mb-4">

                                                <div class="form-outline">
                                                    <label class="form-label" for="firstName">Họ và tên</label> <span class="error text-danger"> * </span>
                                                    <input type="text" name="name" class="form-control form-control-lg" value = "<?php echo $laborer->name?>" required/>
                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-4">

                                                <div class="form-outline">
                                                    <label class="form-label" for="lastName">Số thẻ CCCD</label> <span class="error text-danger"> * </span>
                                                    <input type="text" name="citizenIdentification" class="form-control form-control-lg" value = "<?php echo $laborer->citizenIdentification?>" required/>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-4 d-flex align-items-center">

                                                <div class="form-outline datepicker w-100">
                                                    <label for="birthdayDate" class="form-label">Ngày sinh</label> <span class="error text-danger"> * </span>
                                                    <input type="date" class="form-control form-control-lg" name="birth" value = "<?php echo $laborer->dateOfBirth?>" required/>

                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-4">

                                                <p class="mb-2 pb-1">Giới tính: </p> <span class="error text-danger"> * </span>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="maleGender"
                                                    value="Male" <?php if ($laborer->gender === "Male") echo 'checked'  ?> />
                                                    <label class="form-check-label" for="maleGender">Nam</label>
                                                </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="femaleGender"
                                                value="Female" <?php if ($laborer->gender === "Female") echo 'checked'  ?>/>
                                                <label class="form-check-label" for="femaleGender">Nữ</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="otherGender"
                                                value="Other" <?php if ($laborer->gender === "Other") echo 'checked'  ?>/>
                                                <label class="form-check-label" for="otherGender">Khác</label>
                                            </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-4 pb-2">

                                                <div class="form-outline">
                                                    <label class="form-label" for="address">Địa chỉ</label> <span class="error text-danger"> * </span>
                                                    <input type="text" name="address" class="form-control form-control-lg" value = "<?php echo $laborer->address?>" required/>

                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4 pb-2">

                                                <div class="form-outline">
                                                    <label class="form-label" for="phoneNumber">Số điện thoại</label> <span class="error text-danger"> * </span>
                                                    <input type="tel" name="phone" class="form-control form-control-lg" value = "<?php echo $laborer->phone?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-4 pb-2">

                                                <div class="form-outline">
                                                    <label class="form-label" for="note">Ghi chú</label>
                                                    <input type="text" name="note" class="form-control form-control-lg" value = "<?php echo $laborer->note?>" />

                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4 pb-2">
                                                <label class="form-label select-label">Nhóm</label>
                                                <span class="error text-danger"> * </span>
                                                <select class="form-select form-control-lg" name="team">
                                                    <option value="">Chọn nhóm</option>
                                                    <option value='Team A'>Team A</option>
                                                    <option value='Team B'>Team B</option>
                                                    <option value='Team C'>Team C</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-2">
                                            <input class="btn btn-success btn-lg float-end" type="submit" name="submitedit" value="Sửa" />
                                        </div>

                                    </form>
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
    }
    else {
        header("Location: ../login.php");
        exit();
    }
?>