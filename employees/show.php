<?php
require_once '../includes/connection.php';

// Check if the 'ssn' parameter is set
if (isset($_GET['ssn'])) {
    $ssn = $_GET['ssn'];
} else {
    echo "<h1 align='center'>Wrong page !!!!</h1>";
    exit();
}

// Fetch employee details, department name, and supervisor name
$query = "
    SELECT e.fname, e.lname, e.ssn, e.bdate, e.address, e.gender, e.salary, e.superssn, e.dno,
           d.dname AS department_name,
           s.fname AS supervisor_fname, s.lname AS supervisor_lname,
           e.image AS employee_image
    FROM employee e
    LEFT JOIN department d ON e.dno = d.dnum
    LEFT JOIN employee s ON e.superssn = s.ssn
    WHERE e.ssn = :ssn
";
$stmt = $connection->prepare($query);
$stmt->execute(['ssn' => $ssn]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

include_once "../includes/header.php";
?>

<!-- Header -->
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Employee Details</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Employees</a></li>
                            <li><a href="#">Employee Details</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="animated fadeIn">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">First Name</th>
                                <td><?php echo $employee['fname']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Last Name</th>
                                <td><?php echo $employee['lname']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">SSN</th>
                                <td><?php echo $employee['ssn']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Birthdate</th>
                                <td><?php echo $employee['bdate']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Address</th>
                                <td><?php echo $employee['address']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Gender</th>
                                <td><?php echo $employee['gender']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Salary</th>
                                <td><?php echo $employee['salary']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Department</th>
                                <td><?php echo $employee['department_name']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Supervisor</th>
                                <td><?php echo $employee['supervisor_fname'] . ' ' . $employee['supervisor_lname']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Photo</th>
                                <td><img src="<?php echo "../uploads/images/" . $employee['employee_image']; ?>" style="width:100px; height:100px;" alt="Employee Image"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- .animated -->
</div><!-- .content -->

<?php
include_once "../inlcudes/footer.php";
?>
