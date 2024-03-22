<?php
// Thông tin kết nối đến cơ sở dữ liệu MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "ql_nhansu";

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
}
$records_per_page = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Truy vấn để lấy danh sách thông tin nhân viên theo trang
$sql = "SELECT nhanvien.Ma_NV, nhanvien.Ten_NV, nhanvien.Phai, nhanvien.Noi_Sinh, nhanvien.Ma_Phong, nhanvien.Luong, phongban.Ten_Phong
        FROM nhanvien 
        INNER JOIN phongban ON phongban.Ma_Phong = nhanvien.Ma_Phong
        LIMIT $offset, $records_per_page";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Hiển thị dữ liệu
    echo "<table border='1' color:'red'>
    <tr>
    <th>Mã nhân viên</th>
    <th>Tên nhân viên</th>
    <th>Giới tính</th>
    <th>Nơi sinh</th>
    <th>Tên phòng</th>
    <th>Lương</th>
    <th>Action</th>
    </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Ma_NV"] . "</td>";
        echo "<td>" . $row["Ten_NV"] . "</td>";
        echo "<td>";
        if($row["Phai"] == "NAM") {
            echo "<img src='img/man.jpg' alt='Nam' width='50' height='50'/>";
        } else if($row["Phai"] == "NU") {
            echo "<img src='img/woman.png' alt='Nữ' width='50' height='50'/>";
        }
        echo "</td>";
        //echo "<td>" . $row["Phai"] . "</td>";
        echo "<td>" . $row["Noi_Sinh"] . "</td>";
        echo "<td>" . $row["Ten_Phong"] . "</td>";
        echo "<td>" . $row["Luong"] . "</td>";
        echo "<td>";
        echo "<button onclick='addEmployee(\"" . $row["Ma_NV"] . "\")'>Thêm</button>";
        echo "<button onclick='editEmployee(\"" . $row["Ma_NV"] . "\")'>Sửa</button>";
        echo "<button onclick='deleteEmployee(\"" . $row["Ma_NV"] . "\")'>Xóa</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    $sql = "SELECT COUNT(*) AS total FROM nhanvien";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_records = $row['total'];
    $total_pages = ceil($total_records / $records_per_page);

    echo "<br>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
} else {
    echo "Không có dữ liệu nhân viên";
}


// Đóng kết nối
$conn->close();
?>