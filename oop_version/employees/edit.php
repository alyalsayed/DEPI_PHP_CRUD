<?php
require_once '../includes/header.php';
require_once './classes/Employee.php';

// Initialize the Employee class
$employeeModel = new Employee();

// Check if SSN is provided
if (isset($_GET['ssn'])) {
    $ssn = $_GET['ssn'];
} else {
    echo "<h1 align='center'>Wrong page !!!!</h1>";
    exit();
}

// Fetch employee details and current images
$employeeDetails = $employeeModel->show($ssn);
$employee_data = $employeeDetails['employee'];
$existing_images = $employeeDetails['images'];

// Fetch department options
$dept_data = $employeeModel->getDepartments();

// Fetch supervisors' SSNs
$supervisors = $employeeModel->getSupervisors();

// Handle form submission
if (isset($_POST['edit'])) {
    // Validate and update employee details
    $errors = [];
    $success = false;
    $updateData = [
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'email' => $_POST['email'],
        // 'ssn' => $_POST['ssn'],
        'bdate' => $_POST['bdate'],
        'address' => $_POST['address'],
        'gender' => $_POST['gender'],
        'salary' => $_POST['salary'],
        'superssn' => $_POST['superssn'],
        'dno' => $_POST['department']
    ];

    // Check for image upload
    $imageFiles = !empty($_FILES['images']['name'][0]) ? $_FILES['images'] : [];

    // var_dump(empty($imageFiles['name'][0]));
    // var_dump($updateData);
    // die();
    // Update employee data
    $updateResult = $employeeModel->update($ssn, $updateData, $imageFiles);
    // var_dump($updateResult);
    // die();
    if ($updateResult) {
        $success = true;
      //  header("Location: index.php");
        //exit();
    } else {
        $success = false;

        $errors = $employeeModel->getErrors();
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
                        <?php if (isset($success) && $success && $_SERVER['REQUEST_METHOD'] == 'POST') { ?>
                    <div class="alert alert-success" role="alert">
                        Employee details updated successfully.
                    </div>
                <?php } else if (isset($errors) && !empty($errors)  && $_SERVER['REQUEST_METHOD'] == 'POST') { ?>
                    <div class="alert alert-danger" role="alert">
                       
                             Please fill all fields correctly
                             </div>
                        <?php } ?>
              
                        
                        <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">First Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="fname" placeholder="First Name" class="form-control" value="<?php echo htmlspecialchars($employee_data['fname']); ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">Last Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="lname" placeholder="Last Name" class="form-control" value="<?php echo htmlspecialchars($employee_data['lname']); ?>"></div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="email-input" class="form-control-label">Email</label></div>
                                <div class="col-12 col-md-9"><input type="email" id="email-input" name="email" placeholder="Email" class="form-control" value="<?php echo htmlspecialchars($employee_data['email']); ?>" ></div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">SSN</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="text-input" name="ssn" placeholder="SSN" class="form-control" value="<?php echo htmlspecialchars($employee_data['ssn']); ?>" readonly>
                                </div>
                                <?php if (isset($errors['ssn_required'])) { ?>
                                    <small class="form-text text-muted" style="color:red !important"><?php echo htmlspecialchars($errors['ssn_required']); ?></small>
                                <?php } ?>
                                <?php if (isset($errors['ssn_unique'])) { ?>
                                    <small class="form-text text-muted" style="color:red !important"><?php echo htmlspecialchars($errors['ssn_unique']); ?></small>
                                <?php } ?>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">Birthdate</label></div>
                                <div class="col-12 col-md-9"><input type="date" id="text-input" name="bdate" class="form-control" value="<?php echo htmlspecialchars($employee_data['bdate']); ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">Address</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="address" placeholder="Address" class="form-control" value="<?php echo htmlspecialchars($employee_data['address']); ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">Gender</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="text-input" name="gender" placeholder="Gender" class="form-control" value="<?php echo htmlspecialchars($employee_data['gender']); ?>">
                                </div>
                                <?php if (isset($errors['invalid_gender'])) { ?>
                                    <small class="invalid_gender" style="color:red !important"><?php echo htmlspecialchars($errors['invalid_gender']); ?></small>
                                <?php } ?>
                                <?php if (isset($errors['gender_required'])) { ?>
                                    <small class="invalid_gender" style="color:red !important"><?php echo htmlspecialchars($errors['gender_required']); ?></small>
                                <?php } ?>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class="form-control-label">Salary</label></div>
                                <div class="col-12 col-md-9"><input type="number" id="text-input" name="salary" placeholder="Salary" class="form-control" value="<?php echo (int)htmlspecialchars($employee_data['salary']); ?>"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="select" class="form-control-label">Select Department</label></div>
                                <div class="col-12 col-md-9">
                                    <select name="department" id="select" class="form-control">
                                        <?php foreach ($dept_data as $dept) { ?>
                                            <option value="<?php echo htmlspecialchars($dept['dnum']); ?>" <?php if ($dept['dnum'] == $employee_data['dno']) { ?> selected <?php } ?>><?php echo htmlspecialchars($dept['dname']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                        
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="select" class="form-control-label">Select Supervisor</label></div>
                                <div class="col-12 col-md-9">
                                    <select name="superssn" id="select" class="form-control">
                                        <?php foreach ($supervisors as $supervisor) { ?>
                                            <option value="<?php echo htmlspecialchars($supervisor['ssn']); ?>" <?php if ($supervisor['ssn'] == $employee_data['superssn']) { ?> selected <?php } ?>><?php echo htmlspecialchars($supervisor['fname'] . ' ' . $supervisor['lname'].' ('.$supervisor['ssn'].')'); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>


                            <!-- Images -->
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="images" class="form-control-label">Employee Images</label></div>
                                <div class="col-12 col-md-9">
                                    <?php if (!empty($existing_images)): ?>
                                        <?php foreach ($existing_images as $image): ?>
                                            <img src="../uploads/images/<?php echo htmlspecialchars($image['image_name']); ?>" style="width:100px; height:100px; margin-right: 10px;" alt="Employee Image">
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <input type="file" id="images" name="images[]"  multiple>
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
</div>

<?php include_once '../includes/footer.php'; ?>
