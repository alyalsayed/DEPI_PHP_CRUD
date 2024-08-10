<?php
ob_start();
require_once '../includes/connection.php';
include_once '../includes/header.php';
global $errors;

if (isset($_GET['ssn'])) {
    $ssn = $_GET['ssn'];
} else {
    echo "<h1 align='center'>wrong page !!!!</h1>";
    exit();
}

$employee_result = $connection->query("select * from employee where ssn = $ssn");
$employee_data = $employee_result->fetch(PDO::FETCH_ASSOC);
$dept_result = $connection->query("select * from department");
$dept_data = $dept_result->fetchAll(PDO::FETCH_ASSOC);

function validate($input)
{
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    return $input;
}

if (isset($_POST['edit'])) {
    $fname = validate($_POST['fname']);
    $lname = validate($_POST['lname']);
    $bdate = validate($_POST['bdate']);
    $address = validate($_POST['address']);
    $gender = validate($_POST['gender']);
    $salary = validate($_POST['salary']);
    $superssn = validate($_POST['superssn']);
    $ssn = validate($_POST['ssn']);
    $department = $_POST['department'];

    $result = $connection->query("UPDATE `employee` SET `fname`='$fname', `lname`='$lname', `bdate`='$bdate', `address`='$address', `gender`='$gender', `salary`=$salary, `dno`='$department', `superssn`='$superssn' WHERE ssn=$ssn");
    if ($result) {
        header("location: index.php");
    }
}
?>
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Edit Employee</strong>
                    </div>
                    <div class="card-body card-block">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">First Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="fname" placeholder="First Name" class="form-control" value="<?php echo $employee_data['fname'] ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Last Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="lname" placeholder="Last Name" class="form-control" value="<?php echo $employee_data['lname'] ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">SSN</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="text-input" name="ssn" placeholder="SSN" class="form-control" value="<?php echo $employee_data['ssn'] ?>">
                                </div>
                                <?php if (isset($errors['ssn_required'])) { ?>
                                    <small class="form-text text-muted" style="color:red !important"><?php echo $errors['ssn_required'] ?></small>
                                <?php } ?>
                                <?php if (isset($errors['ssn_unique'])) { ?>
                                    <small class="form-text text-muted" style="color:red !important"><?php echo $errors['ssn_unique'] ?></small>
                                <?php } ?>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Birthdate</label></div>
                                <div class="col-12 col-md-9"><input type="date" id="text-input" name="bdate" class="form-control" value="<?php echo $employee_data['bdate'] ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Address</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="address" placeholder="Address" class="form-control" value="<?php echo $employee_data['address'] ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Gender</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="text-input" name="gender" placeholder="Gender" class="form-control" value="<?php echo $employee_data['gender'] ?>">
                                </div>
                                <?php if (isset($errors['invalid_gender'])) { ?>
                                    <small class="invalid_gender" style="color:red !important"><?php echo $errors['invalid_gender'] ?></small>
                                <?php } ?>
                                <?php if (isset($errors['gender_required'])) { ?>
                                    <small class="invalid_gender" style="color:red !important"><?php echo $errors['gender_required'] ?></small>
                                <?php } ?>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Salary</label></div>
                                <div class="col-12 col-md-9"><input type="number" id="text-input" name="salary" placeholder="Salary" class="form-control" value="<?php echo $employee_data['salary'] ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="select" class=" form-control-label">Select Department</label></div>
                                <div class="col-12 col-md-9">
                                    <select name="department" id="select" class="form-control">
                                        <?php foreach ($dept_data as $dept) { ?>
                                            <option value="<?php echo $dept['dnum'] ?>" <?php if ($dept['dnum'] == $employee_data['dno']) { ?> selected <?php } ?>><?php echo $dept['dname'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Supervisor SSN</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="text-input" name="superssn" placeholder="Supervisor SSN" class="form-control" value="<?php echo $employee_data['superssn']  ?>" readonly>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm" name="edit">
                                    <i class="fa fa-dot-circle-o"></i> Edit
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include_once '../includes/footer.php';
ob_end_flush();
?>
