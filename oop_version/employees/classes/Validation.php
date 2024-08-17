<?php
trait Validation
{
    private $errors = [];
    private $connection;

    public function setConnection($dbConnection)
    {
        $this->connection = $dbConnection;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate($input)
    {
        $input = trim($input);
        $input = htmlspecialchars($input);
        $input = stripslashes($input);
        $input = strip_tags($input);
        return $input;
    }

    public function checkEmpty($value, $errorKey, $errorMessage)
    {
        if (empty($value)) {
            $this->errors[$errorKey] = $errorMessage;
        }
    }

    public function validateInteger($value, $errorKey, $errorMessage)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$errorKey] = $errorMessage;
        }
    }

    public function validateEmail($email, $errorKey, $errorMessage)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$errorKey] = $errorMessage;
        }
    }

    public function validateAddress($address, $errorKey, $errorMessage)
    {
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $address)) {
            $this->errors[$errorKey] = $errorMessage;
        }
    }

    public function checkUniqueness($table, $column, $value, $errorKey, $errorMessage)
    {
        if ($this->connection) {
            $query = "SELECT $column FROM $table WHERE $column = :value";
            $statement = $this->connection->prepare($query);
            $statement->execute([':value' => $value]);

            if ($statement->rowCount() > 0) {
                $this->errors[$errorKey] = $errorMessage;
            }
        } else {
            throw new Exception('Database connection is not set.');
        }
    }

    public function validateName($name, $errorKey, $errorMessage)
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $this->errors[$errorKey] = $errorMessage;
        }
    }

    public function validateImages($imageFiles, $ssn)
    {
        $allowedExt = ['png', 'jpg', 'jpeg'];
        $uploadedImages = [];

        for ($i = 0; $i < count($imageFiles['name']); $i++) {
            $imageName = $imageFiles['name'][$i];
            $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
            $uniqueImageName = $ssn . '_' . $i . '.' . $imageExt; // Unique name using SSN and index
            $tmpName = $imageFiles['tmp_name'][$i];
            $path = "../uploads/images/$uniqueImageName";

            if (!in_array($imageExt, $allowedExt)) {
                $this->errors['images'][] = "Invalid format for image $imageName";
            } else {
                if (move_uploaded_file($tmpName, $path)) {
                    $uploadedImages[] = $uniqueImageName;
                } else {
                    $this->errors['images'][] = "Failed to upload image $imageName";
                }
            }
        }

        return $uploadedImages;
    }
    public function checkImageUniqueness($imageName, $employee_ssn, $errorKey, $errorMessage)
    {
        if ($this->connection) {
            // Prepare the query to check for existing image names
            $query = "SELECT COUNT(*) FROM employee_images WHERE image_name = :image_name AND employee_ssn = :employee_ssn";
            $statement = $this->connection->prepare($query);
            
            // Execute the query with parameters
            $statement->execute([
                ':image_name' => $imageName,
                ':employee_ssn' => $employee_ssn
            ]);
    
            // Fetch the result
            $count = $statement->fetchColumn();
    
            // If count is greater than 0, the image already exists
            if ($count > 0) {
                $this->errors[$errorKey][] = $errorMessage;
                return false; // Image is not unique
            }
    
            return true; // Image is unique
        }
    
        return false; // No connection
    }
    



    public function validateInput($data)
    {
        $fname = $this->validate($data['fname']);
        $lname = $this->validate($data['lname']);
        $email = $this->validate($data['email']);
        if(isset($data["ssn"])) { $ssn = $this->validate($data['ssn']); }
        $bdate = $this->validate($data['bdate']);
        $address = $this->validate($data['address']);
        $gender = $this->validate($data['gender']);
        $salary = $this->validate($data['salary']);
        $superssn = $this->validate($data['superssn']);
        $dno = $this->validate($data['dno']);

        // Validate name
        $this->checkEmpty($fname, 'fname_required', "Please enter your first name.");
        $this->validateName($fname, 'invalid_fname', "Only letters and white space allowed");

        $this->checkEmpty($lname, 'lname_required', "Please enter your last name.");
        $this->validateName($lname, 'invalid_lname', "Only letters and white space allowed");

        // Validate email
        $this->checkEmpty($email, 'email_required', "Please enter your email.");
        $this->validateEmail($email, 'invalid_email', "Please enter a valid email address.");

        // Validate SSN
        if(isset($data["ssn"])) {
        $this->checkEmpty($ssn, 'ssn_required', "Please enter your SSN.");
        $this->validateInteger($ssn, 'invalid_ssn', "The SSN must be a number.");
        $this->checkUniqueness('employee', 'ssn', $ssn, 'ssn_unique', 'This SSN already exists.');
        }
        

        // Validate birth date
        $this->checkEmpty($bdate, 'bdate_required', "Please enter your birth date.");

        // Validate address
        $this->checkEmpty($address, 'address_required', "Please enter your address.");
        $this->validateAddress($address, 'invalid_address', "Only letters, numbers, and white space are allowed.");

        // Validate salary
        $this->checkEmpty($salary, 'salary_required', "Please enter your salary.");
        $this->validateInteger($salary, 'invalid_salary', "The salary must be a number.");

        // Validate department number
        $this->checkEmpty($dno, 'dno_required', "Please select your department.");
        $this->validateInteger($dno, 'invalid_dno', "The department number must be a number.");

        // Validate gender
        $this->checkEmpty($gender, 'gender_required', "Gender is required.");
        
        

    
    }
}
?>
