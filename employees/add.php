<?php

require_once '../includes/connection.php';
include_once "../includes/header.php";

// Fetch departments data
$dept_result = $connection->query("SELECT * FROM department");
$dept_data = $dept_result->fetchAll(PDO::FETCH_ASSOC);

// Fetch supervisors' SSNs
$superssn_result = $connection->query("SELECT ssn, fname, lname FROM employee");
$superssn_data = $superssn_result->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

/*  ==========================================
          Function to validate inputs
    ==========================================
*/
function validate($input)
{
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    return $input;
}

/*  ==========================================
      Function to check if input is integer
    ==========================================
*/
function check_int($input, $key, $error)
{
    global $errors;
    if (!filter_var($input, FILTER_VALIDATE_INT)) {
        $errors[$key] = $error;
    }
}

/*  ==========================================
      Function to check if input is unique
    ==========================================
*/
function check_unique($table, $column, $input, $key, $error)
{
    global $errors;
    global $connection;
    $input_result = $connection->query("SELECT $column FROM $table WHERE $column='$input'");
    $input_count = $input_result->rowCount();
    if ($input_count > 0) {
        $errors[$key] = $error;
    }
}

/*  ==========================================
      If data is submitted via submit button
    ==========================================
*/
if (isset($_POST['add'])) {

    $fname = validate($_POST['fname']);
    $lname = validate($_POST['lname']);
    $ssn = validate($_POST['ssn']);
    $bdate = validate($_POST['bdate']);
    $address = validate($_POST['address']);
    $gender = $_POST['gender'];
    $salary = validate($_POST['salary']);
    $superssn = validate($_POST['superssn']);
    $dno = $_POST['dno'];

    $image_name = $_FILES['image']['name'];
    $image_name = $ssn . $image_name;
    $tmp_name = $_FILES['image']['tmp_name'];
    $path = "../uploads/images/$image_name";
    $allowed_ext = ['png', 'jpg', 'jpeg'];
    $str_arr = explode(".", $image_name);
    $ext = end($str_arr);
    if (!in_array($ext, $allowed_ext)) {
        $errors['image'] = "invalid format";
    }

    if (empty($fname)) {
        $errors['fname_required'] = "please enter your first name..";
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
            $errors['invalid_fname'] = "Only letters and white space allowed";
        }
        $fname = strtolower($fname);
        $fname = ucwords($fname);
    }

    if (empty($lname)) {
        $errors['lname_required'] = "please enter your last name..";
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $errors['invalid_lname'] = "Only letters and white space allowed";
        }
        $lname = strtolower($lname);
        $lname = ucwords($lname);
    }

    if (empty($ssn)) {
        $errors['ssn_required'] = "please enter your SSN..";
    } else {
        check_int($ssn, 'invalid_ssn', "the SSN must be a number");
        check_unique('employee', 'ssn', $ssn, 'ssn_unique', 'this SSN already exists ..');
    }

    if (empty($bdate)) {
        $errors['bdate_required'] = "please enter your birth date..";
    }

    if (empty($address)) {
        $errors['address_required'] = "Please enter your address.";
    } else {
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $address)) {
            $errors['invalid_address'] = "Only letters, numbers, and white space are allowed.";
        }
    }
    

    if (empty($salary)) {
        $errors['salary_required'] = "please enter your salary..";
    } else {
        check_int($salary, 'invalid_salary', "the salary must be a number");
    }

    if (empty($dno)) {
        $errors['dno_required'] = "please select your department..";
    } else {
        check_int($dno, 'invalid_dno', "the department number must be a number");
    }

    if (empty($gender)) {
        $errors["gender_required"] = "Gender is required";
    }

    if (empty($errors)) {
        move_uploaded_file($tmp_name, $path);
        $result = $connection->query("INSERT INTO `employee`(`fname`, `lname`, `ssn`, `bdate`, `address`, `gender`, `salary`, `superssn`, `dno`, `image`) VALUES ('$fname','$lname','$ssn','$bdate','$address','$gender','$salary','$superssn','$dno','$image_name')");
    }
}
?>


<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Add Employee</strong>
                    </div>
                    <div class="card-body card-block">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-fname" class="form-control-label">First Name</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="input-fname" name="fname" placeholder="Enter your first name" class="form-control">
                                    <?php if (isset($errors['fname_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['fname_required'] ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_fname'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_fname'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-lname" class="form-control-label">Last Name</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="input-lname" name="lname" placeholder="Enter your last name" class="form-control">
                                    <?php if (isset($errors['lname_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['lname_required'] ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_lname'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_lname'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-ssn" class="form-control-label">SSN</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="input-ssn" name="ssn" placeholder="Enter your SSN" class="form-control">
                                    <?php if (isset($errors['ssn_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['ssn_required'] ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['ssn_unique'])) { ?>
                                        <small class="text-danger"><?php echo $errors['ssn_unique'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-bdate" class="form-control-label">Birth Date</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="input-bdate" name="bdate" class="form-control">
                                    <?php if (isset($errors['bdate_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['bdate_required'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-address" class="form-control-label">Address</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="input-address" name="address" placeholder="Enter your address" class="form-control">
                                    <?php if (isset($errors['address_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['address_required'] ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_address'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_address'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-gender" class="form-control-label">Gender</label></div>
                                <div class="col-12 col-md-9">
                                    <label for="gender-input-male">
                                        <input type="radio" id="gender-input-male" name="gender" value="m"> Male
                                    </label>
                                    <label for="gender-input-female">
                                        <input type="radio" id="gender-input-female" name="gender" value="f"> Female
                                    </label>
                                    <?php if (isset($errors['gender_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['gender_required'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="input-salary" class="form-control-label">Salary</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="input-salary" name="salary" placeholder="Enter your salary" class="form-control">
                                    <?php if (isset($errors['salary_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['salary_required'] ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_salary'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_salary'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="select-superssn" class="form-control-label">Select Supervisor SSN</label></div>
                                <div class="col-12 col-md-9">
                                    <select name="superssn" id="select-superssn" class="form-control">
                                        <?php foreach ($superssn_data as $supervisor) { ?>
                                            <option value="<?php echo $supervisor['ssn'] ?>">
                                                <?php echo $supervisor['fname'] . ' ' . $supervisor['lname'] . ' (' . $supervisor['ssn'] . ')' ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>


                            <div class="row form-group">
                                <div class="col col-md-3"><label for="select-dno" class="form-control-label">Department</label></div>
                                <div class="col-12 col-md-9">
                                    <select name="dno" id="select-dno" class="form-control">
                                        <?php foreach ($dept_data as $dept) { ?>
                                            <option value="<?php echo $dept['dnum'] ?>"><?php echo $dept['dname'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($errors['dno_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['dno_required'] ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_dno'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_dno'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="image-input" class="form-control-label">Profile Photo</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="file" id="image-input" name="image" class="form-control">
                                    <?php if (isset($errors['image'])) { ?>
                                        <small class="text-danger"><?php echo $errors['image'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm" name="add">
                                    <i class="fa fa-dot-circle-o"></i> Submit
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
</div>

<?php include_once '../includes/footer.php'; ?>