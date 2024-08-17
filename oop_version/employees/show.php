<?php
require_once "./classes/Employee.php";

$employeeModel = new Employee();

// Check if the 'ssn' parameter is set
if (isset($_GET['ssn'])) {
    $ssn = $_GET['ssn'];
} else {
    echo "<h1 align='center'>Wrong page !!!!</h1>";
    exit();
}

$data = $employeeModel->show($ssn);
$employee = $data['employee'];
$images = $data['images'];

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
                                <td><?php echo htmlspecialchars($employee['fname']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Last Name</th>
                                <td><?php echo htmlspecialchars($employee['lname']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">SSN</th>
                                <td><?php echo htmlspecialchars($employee['ssn']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Birthdate</th>
                                <td><?php echo htmlspecialchars($employee['bdate']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Address</th>
                                <td><?php echo htmlspecialchars($employee['address']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Gender</th>
                                <td><?php echo htmlspecialchars($employee['gender']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Salary</th>
                                <td><?php echo htmlspecialchars($employee['salary']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Department</th>
                                <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Supervisor</th>
                                <td><?php echo htmlspecialchars($employee['supervisor_fname']) . ' ' . htmlspecialchars($employee['supervisor_lname']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Photos</th>
                                <td>
                                    <?php foreach ($images as $image): ?>
                                        <img src="<?php echo "../uploads/images/" . htmlspecialchars($image['image_name']); ?>" style="width:100px; height:100px; margin-right: 10px;" alt="Employee Image">
                                    <?php endforeach; ?>
                                </td>
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
include_once "../includes/footer.php";
?>
