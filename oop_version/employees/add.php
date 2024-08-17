<?php
require_once './classes/Employee.php';
include_once "../includes/header.php";

// Create an instance of the Employee class
$employee = new Employee();
$connection = $employee->getConnection();

// Fetch departments data

$dept_data = $employee->getDepartments();

// Fetch supervisors' SSNs
$superssn_data =  $employee->getSupervisors();
$errors = [];
$sucess = false;

/*  ==========================================
      Handle form submission
    ==========================================
*/
if (isset($_POST['add'])) {
    // var_dump($_POST);
    // die();    
    // Collect form data
    $data = [
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'ssn' => $_POST['ssn'],
        'email' => $_POST['email'],
        'bdate' => $_POST['bdate'],
        'address' => $_POST['address'],
        'gender' => $_POST['gender'],
        'salary' => $_POST['salary'],
        'superssn' => $_POST['superssn'],
        'dno' => $_POST['dno']
    ];

    // Handle multiple images
    $image_files = $_FILES['images'];
    
    // Validate and create employee
    $result = $employee->create($data, $image_files);

    if ($result) {
        $sucess = true;
    } else {
        $errors = $employee->getErrors();
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
                            <?php if ($sucess) { ?>
                                <div class="alert alert-success" role="alert">
                                    Employee added successfully
                                </div>
                            <?php } else if( isset($errors) && !empty($errors) && $_SERVER["REQUEST_METHOD"] == "POST" )  { ?>
                                <div class="alert alert-danger" role="alert">
                                   
                                        <?php echo "Please fill all fields correctly"; ?>
                           
                                </div>
                            <?php } ?>

                           
                        
                        <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-fname" class="form-control-label">First Name</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="input-fname" name="fname" placeholder="Enter your first name" class="form-control">
                                    <?php if (isset($errors['fname_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['fname_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_fname'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_fname']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-lname" class="form-control-label">Last Name</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="input-lname" name="lname" placeholder="Enter your last name" class="form-control">
                                    <?php if (isset($errors['lname_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['lname_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_lname'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_lname']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-email" class="form-control-label">Email</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="email" id="input-email" name="email" placeholder="Enter your email" class="form-control">
                                    <?php if (isset($errors['email_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['email_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['email_unique'])) { ?>
                                        <small class="text-danger"><?php echo $errors['email_unique']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-ssn" class="form-control-label">SSN</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="input-ssn" name="ssn" placeholder="Enter your SSN" class="form-control">
                                    <?php if (isset($errors['ssn_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['ssn_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['ssn_unique'])) { ?>
                                        <small class="text-danger"><?php echo $errors['ssn_unique']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-bdate" class="form-control-label">Birth Date</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="input-bdate" name="bdate" class="form-control">
                                    <?php if (isset($errors['bdate_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['bdate_required']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-address" class="form-control-label">Address</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="input-address" name="address" placeholder="Enter your address" class="form-control">
                                    <?php if (isset($errors['address_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['address_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_address'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_address']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-gender" class="form-control-label">Gender</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <label for="gender-input-male">
                                        <input type="radio" id="gender-input-male" name="gender" value="m" checked> Male
                                    </label>
                                    <label for="gender-input-female">
                                        <input type="radio" id="gender-input-female" name="gender" value="f"> Female
                                    </label>
                                    <?php if (isset($errors['gender_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['gender_required']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="input-salary" class="form-control-label">Salary</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="input-salary" name="salary" placeholder="Enter your salary" class="form-control">
                                    <?php if (isset($errors['salary_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['salary_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_salary'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_salary']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="select-superssn" class="form-control-label">Select Supervisor SSN</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="superssn" id="select-superssn" class="form-control">
                                        <?php foreach ($superssn_data as $supervisor) { ?>
                                            <option value="<?php echo $supervisor['ssn']; ?>">
                                                <?php echo $supervisor['fname'] . ' ' . $supervisor['lname'] . ' (' . $supervisor['ssn'] . ')'; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="select-dno" class="form-control-label">Department</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="dno" id="select-dno" class="form-control">
                                        <?php foreach ($dept_data as $dept) { ?>
                                            <option value="<?php echo $dept['dnum']; ?>"><?php echo $dept['dname']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($errors['dno_required'])) { ?>
                                        <small class="text-danger"><?php echo $errors['dno_required']; ?></small>
                                    <?php } ?>
                                    <?php if (isset($errors['invalid_dno'])) { ?>
                                        <small class="text-danger"><?php echo $errors['invalid_dno']; ?></small>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="image-input" class="form-control-label">Profile Photos</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="file" id="image-input" name="images[]" class="form-control" multiple>
                                    <?php if (isset($errors['image'])) { ?>
                                        <small class="text-danger"><?php echo $errors['image']; ?></small>
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
