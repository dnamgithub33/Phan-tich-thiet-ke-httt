<?php
    require_once "C:/xampp/htdocs/pttk/DAO/DAO.php";
    require_once 'C:/xampp/htdocs/pttk/Model/Laborer.php';
    require_once 'C:/xampp/htdocs/pttk/Model/Manager.php';
    function checkUnique($newlaborer, $con, $view)
    {
        // Kiểm tra xem citizenIdentificationID đã tồn tại hay chưa
        $count = 0;
        $sqlCheck = "SELECT COUNT(*) FROM tblmember WHERE citizenIdentificationID = ?";
        $stmtCheck = $con->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $newlaborer->citizenIdentification);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            header("location: ../View/manager/$view.php?id=$newlaborer->id&err=cccd");
            exit();
        }
    }
    // Hàm validate data không chứa ký tự đặc biệt
    function validation($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // Hàm chuyển đổi tên
    function convertNameToUsername($fullName) 
    {
        // Tách các phần của tên bằng khoảng trắng
        $nameParts = explode(" ", trim($fullName));
        
        // Lấy tên chính (phần cuối cùng)
        $lastName = array_pop($nameParts);
        
        // Lấy chữ cái đầu của các phần còn lại
        $initials = "";
        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    
        // Kết hợp tên chính với các chữ cái đầu
        return $lastName . $initials;
    }
    // Hàm login
    function checkLogin($username, $password, $con)
    {
        session_start();
        $username = validation($username);
        $password = md5(validation($password)); // Mã hóa mật khẩu bằng MD5

        // Kiểm tra xem username và password có rỗng không
        if (empty($username)) {
            header("Location: View/login.php?error=Chưa nhập tài khoản");
            exit();
        }

        if (empty($password)) {
            header("Location: View/login.php?error=Chưa nhập mật khẩu");
            exit();
        }

        // Câu lệnh SQL để lấy thông tin người dùng
        $query = "SELECT * FROM tblMember WHERE username='$username' AND password='$password'";
        $result = $con->query($query);

        // Kiểm tra số lượng dòng kết quả trả về
        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Tạo đối tượng Member
            require_once "../Model/Member.php";
            $member = new Member(
                $row['ID'], 
                $row['username'], 
                $row['password'], 
                $row['name'], 
                $row['dateOfBirth'], 
                $row['gender'], 
                $row['phone'], 
                $row['address'], 
                $row['citizenIdentification'], 
                $row['role'], 
                $row['note']
            );

            // Lưu đối tượng Member vào session
            $_SESSION['member'] = serialize($member);

            // Điều hướng dựa trên vai trò của người dùng
            if ($member->role === 'Manager') {
                header("Location: ../View/manager/home.php");
                exit();
            } else {
                header("Location: ../View/laborer/home.php");
                exit();
            }
        } else {
            // Sai tài khoản hoặc mật khẩu
            header("Location: View/login.php?error=Tài khoản hoặc mật khẩu chưa đúng");
            exit();
        }
    }
    function searchLaborer($keyword, $by)
    {
        
        global $con;
        $laborers = [];

        // Câu lệnh SQL để lấy thông tin người lao động
        $sql = "";
        if($by === 1){
            if(empty($keyword)){
                $sql = "SELECT *
                    FROM tblmember 
                    INNER JOIN tbllaborer ON tblmember.ID = tbllaborer.tblMemberID 
                    WHERE tblmember.role = 'Laborer'";
            } else {
                $keyword = validation($keyword);
                $sql = "SELECT *
                    FROM tblmember 
                    INNER JOIN tbllaborer ON tblmember.ID = tbllaborer.tblMemberID 
                    WHERE tblmember.role = 'Laborer' AND tblmember.name LIKE '%$keyword%'";
            }
            // Thực thi truy vấn
            $result = $con->query($sql);

            // Xử lý kết quả
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $laborer = new Laborer(
                        $row['ID'],
                        $row['username'],
                        $row['password'],
                        $row['name'],
                        $row['dateOfBirth'],
                        $row['gender'],
                        $row['phone'],
                        $row['address'],
                        $row['citizenIdentificationID'],
                        $row['role'],
                        $row['note'],
                        $row['team'],
                        $row['joinDate']
                    );

                    $laborers[] = $laborer;
                }
            }

            return $laborers;
        } else {
            $keyword = validation($keyword);
            $sql = "SELECT *
                    FROM tblmember 
                    INNER JOIN tbllaborer ON tblmember.ID = tbllaborer.tblMemberID 
                    WHERE tblmember.ID = $keyword";
            // Thực thi truy vấn
            $result = $con->query($sql);
            if ($result && $result->num_rows > 0) {
                // Lấy dòng đầu tiên từ kết quả
                $row = $result->fetch_assoc();
            
                // Tạo đối tượng Laborer từ dữ liệu
                $laborer = new Laborer(
                    $row['ID'],
                    $row['username'],
                    $row['password'],
                    $row['name'],
                    $row['dateOfBirth'],
                    $row['gender'],
                    $row['phone'],
                    $row['address'],
                    $row['citizenIdentificationID'],
                    $row['role'],
                    $row['note'],
                    $row['team'],
                    $row['joinDate']
                );
                $_SESSION['laboreredit'] = serialize($laborer);
                return $laborer; // Trả về đối tượng Laborer
            }
        }
        
        return null;

        

    }
    function searchManager($keyword)
    {
        global $con;
        $keyword = validation($keyword);
        $sql = "SELECT *
                FROM tblmember 
                INNER JOIN tblmanager ON tblmember.ID = tblmanager.tblMemberID 
                WHERE tblmember.ID = $keyword";
        // Thực thi truy vấn
        $result = $con->query($sql);
        if ($result && $result->num_rows > 0) {
            // Lấy dòng đầu tiên từ kết quả
            $row = $result->fetch_assoc();
        
            // Tạo đối tượng Manager từ dữ liệu
            $manager = new Manager(
                $row['ID'],
                $row['username'],
                $row['password'],
                $row['name'],
                $row['dateOfBirth'],
                $row['gender'],
                $row['phone'],
                $row['address'],
                $row['citizenIdentificationID'],
                $row['role'],
                $row['note'],
                $row['position']
            );
            return $manager; // Trả về đối tượng Laborer
        }
    }
    function deleteLaborer($id)
    {
        global $con;
        $query = "DELETE FROM tblmember WHERE ID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    function addLaborer($newlaborer, $con)
    {
        checkUnique($newlaborer, $con, "addlaborer");
        // Chèn dữ liệu vào bảng `tblmember`
        $sqlMember = "INSERT INTO tblmember (username, password, name, dateOfBirth, gender, phone, address, citizenIdentificationID, role, note)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtMember = $con->prepare($sqlMember);
        $stmtMember->bind_param(
            "ssssssssss",
            $newlaborer->username,
            $newlaborer->password,
            $newlaborer->name,
            $newlaborer->dateOfBirth,
            $newlaborer->gender,
            $newlaborer->phone,
            $newlaborer->address,
            $newlaborer->citizenIdentification,
            $newlaborer->role,
            $newlaborer->note
        );

        // Thực thi lệnh chèn vào `tblmember`
        if ($stmtMember->execute()) {
            // Lấy ID của thành viên vừa được thêm
            $memberId = $con->insert_id;

            // Chèn dữ liệu vào bảng `tbllaborer`
            $sqlLaborer = "INSERT INTO tbllaborer (tblMemberID, team, joinDate) VALUES (?, ?, ?)";
            $stmtLaborer = $con->prepare($sqlLaborer);
            $stmtLaborer->bind_param("iss", $memberId, $newlaborer->team, $newlaborer->joinDate);

            // Thực thi lệnh chèn vào `tbllaborer`
            $stmtLaborer->execute();
        }
        header("Location: ../View/manager/laborerlist.php");
        exit();
    }
    function updateLaborer($newlaborer, $con)
    {
        checkUnique($newlaborer, $con, "editlaborer");
        // Chèn dữ liệu vào bảng `tblmember`
        $sqlMember = "UPDATE tblmember 
                    SET 
                        username = ?, 
                        password = ?, 
                        name = ?, 
                        dateOfBirth = ?, 
                        gender = ?, 
                        phone = ?, 
                        address = ?, 
                        citizenIdentificationID = ?, 
                        role = ?, 
                        note = ?
                    WHERE ID = ?;
                    ";
        $stmtMember = $con->prepare($sqlMember);
        $stmtMember->bind_param(
            "ssssssssssi",
            $newlaborer->username,
            $newlaborer->password,
            $newlaborer->name,
            $newlaborer->dateOfBirth,
            $newlaborer->gender,
            $newlaborer->phone,
            $newlaborer->address,
            $newlaborer->citizenIdentification,
            $newlaborer->role,
            $newlaborer->note,
            $newlaborer->id
        );
    
        // Thực thi lệnh chèn vào `tblmember`
        if ($stmtMember->execute()) {
    
            // Chèn dữ liệu vào bảng `tbllaborer`
            $sqlLaborer = "UPDATE tbllaborer 
                            SET 
                                team = ?, 
                                joinDate = ?
                            WHERE tblMemberID = ?;
                            ";
            $stmtLaborer = $con->prepare($sqlLaborer);
            $stmtLaborer->bind_param("ssi", $newlaborer->team, $newlaborer->joinDate, $newlaborer->id);
    
            // Thực thi lệnh chèn vào `tbllaborer`
            $stmtLaborer->execute();
        }
        header("Location: ../View/manager/laborerlist.php");
        exit();
    }




    // Xử lý các yêu cầu POST
    // Xử lý thêm người lao động
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['name']) &&
        isset($_POST['citizenIdentification']) &&
        isset($_POST['birth']) &&
        isset($_POST['gender']) &&
        isset($_POST['address']) &&
        isset($_POST['phone']) &&
        isset($_POST['note']) &&
        isset($_POST['team']) &&
        isset($_POST['submit'])
    ){
        $newlaborer = new Laborer(
            1,
            convertNameToUsername($_POST['name']),
            md5("1"),
            $_POST['name'],
            $_POST['birth'],
            $_POST['gender'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['citizenIdentification'],
            "Laborer",
            $_POST['note'],
            $_POST['team'],
            20210101
        );
        addLaborer($newlaborer, $con);
    }


    // Xử lý sửa thông tin người lao động
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['name']) &&
        isset($_POST['citizenIdentification']) &&
        isset($_POST['birth']) &&
        isset($_POST['gender']) &&
        isset($_POST['address']) &&
        isset($_POST['phone']) &&
        isset($_POST['note']) &&
        isset($_POST['team']) &&
        isset($_POST['submitedit'])
    ){
        session_start();
        $oldlaborer = unserialize($_SESSION['laboreredit']);
        $newlaborer = new Laborer(
            $oldlaborer->id,
            $oldlaborer->username,
            $oldlaborer->password,
            $_POST['name'],
            $_POST['birth'],
            $_POST['gender'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['citizenIdentification'],
            "Laborer",
            $_POST['note'],
            $_POST['team'],
            $oldlaborer->joinDate
        );
        unset($_SESSION['laboreredit']);
        updateLaborer($newlaborer, $con);
    }
    // Xử lý checkLogin
    if (isset($_POST['submit'])&&isset($_POST['username'])&&isset($_POST['password']))
    {
        checkLogin($_POST['username'],$_POST['password'],$con);
    }     
    
    

    // Xử lý các yêu cầu GET
    
?>