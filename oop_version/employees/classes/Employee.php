<?php

require_once "./classes/Database.php";
require_once "./classes/Crud.php";
require_once "./classes/Validation.php";

class Employee extends Database implements Crud
{
    use Validation;

    public function __construct()
    {
        parent::__construct(); // Ensure parent constructor is called
        $this->setConnection($this->getConnection());
    }

    public function index()
    {
        $connection = $this->getConnection();
        $query = 'SELECT fname, lname, ssn, dno, salary FROM employee';
        $statement = $connection->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function show($ssn)
    {
        $connection = $this->getConnection();

        $query = "
            SELECT 
                e.fname, 
                e.lname, 
                e.ssn, 
                e.email,
                e.bdate AS bdate, 
                e.address, 
                e.gender, 
                e.salary, 
                e.superssn , 
                e.dno, 
                d.dname AS department_name, 
                s.fname AS supervisor_fname, 
                s.lname AS supervisor_lname 
            FROM employee e 
            LEFT JOIN department d ON e.dno = d.dnum 
            LEFT JOIN employee s ON e.superssn = s.ssn 
            WHERE e.ssn = :ssn
        ";

        $statement = $connection->prepare($query);
        $statement->execute(['ssn' => $ssn]);

        $employee = $statement->fetch(PDO::FETCH_ASSOC);

        $imageQuery = "SELECT image_name FROM employee_images WHERE employee_ssn = :ssn";
        $imageStatement = $connection->prepare($imageQuery);
        $imageStatement->execute(['ssn' => $ssn]);

        $images = $imageStatement->fetchAll(PDO::FETCH_ASSOC);

        return [
            'employee' => $employee,
            'images' => $images
        ];
    }

    public function read($ssn)
    {
        $connection = $this->getConnection();

        $query = "SELECT * FROM employee WHERE ssn = :ssn";
        $statement = $connection->prepare($query);
        $statement->execute(['ssn' => $ssn]);

        $employee = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return false; // Record not found
        }

        return $employee;
    }

    public function create($data, $imageFiles = [])
    {
        $connection = $this->getConnection();

        // Validate input data
        $this->validateInput($data);
        $uploadedImages = $this->validateImages($imageFiles, $data['ssn']);

        if (!empty($this->getErrors())) {
           
            return false; // Validation failed
        }

        $query = "
            INSERT INTO employee (fname, lname, ssn, email, bdate, address, gender, salary, superssn, dno) 
            VALUES (:fname, :lname, :ssn, :email, :bdate, :address, :gender, :salary, :superssn, :dno)
        ";

        $statement = $connection->prepare($query);
        $result = $statement->execute([
            ':fname' => $data['fname'],
            ':lname' => $data['lname'],
            ':ssn' => $data['ssn'],
            ':email' => $data['email'],
            ':bdate' => $data['bdate'],
            ':address' => $data['address'],
            ':gender' => $data['gender'],
            ':salary' => $data['salary'],
            ':superssn' => $data['superssn'],
            ':dno' => $data['dno']
        ]);

        if ($result) {
            $this->saveImages($data['ssn'], $uploadedImages);
            return true;
        }

        return false;
    }

    public function update($ssn, $data, $imageFiles = [])
    {
        $connection = $this->getConnection();
    
        // Validate input data
        $this->validateInput($data);
    
        // Save images and get the result
        $result = true;
        if (!empty($imageFiles['name'][0])) {
            $this->deleteImages($ssn);
            $result = $this->validateImages($imageFiles, $ssn);
           
        }
    
        if (!empty($this->getErrors())) {
                echo "Error";
            return false; // Validation failed
        }
    
        $query = "
            UPDATE employee 
            SET fname = :fname, lname = :lname, email = :email, bdate = :bdate, address = :address, 
                gender = :gender, salary = :salary, superssn = :superssn, dno = :dno
            WHERE ssn = :ssn
        ";
    
        $statement = $connection->prepare($query);
        $updateResult = $statement->execute([
            ':fname' => $data['fname'],
            ':lname' => $data['lname'],
            ':email' => $data['email'],
            ':bdate' => $data['bdate'],
            ':address' => $data['address'],
            ':gender' => $data['gender'],
            ':salary' => $data['salary'],
            ':superssn' => $data['superssn'],
            ':dno' => $data['dno'],
            ':ssn' => $ssn
        ]);
    
        if ($updateResult && $result) {
            $this->saveImages($ssn, $imageFiles['name']);
            return true;
        }
    
        return false;
    }
    
    public function delete($ssn)
    {
        $connection = $this->getConnection();

        // Delete images associated with the employee
        $this->deleteImages($ssn);

        // Delete the employee record
        $query = "DELETE FROM employee WHERE ssn = :ssn";
        $statement = $connection->prepare($query);
        return $statement->execute(['ssn' => $ssn]);
    }

    private function saveImages($ssn, $images)
    {
        $connection = $this->getConnection();
        if (!empty($images)) {
             
        $query = "INSERT INTO employee_images (employee_ssn, image_name) VALUES (:ssn, :image_name)";
        $statement = $connection->prepare($query);
        $j=-1;
        foreach ($images as $image) {
            $j++;
            $imageExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $statement->execute([
                ':ssn' => $ssn,
                ':image_name' => $ssn . "_" .$j. '.'.$imageExt
            ]);
        }
    }
    }
    
    
    private function deleteImages($ssn)
    {
        $connection = $this->getConnection();

        // Delete images from the file system
        $imageQuery = "SELECT image_name FROM employee_images WHERE employee_ssn = :ssn";
        $imageStatement = $connection->prepare($imageQuery);
        $imageStatement->execute(['ssn' => $ssn]);
        $images = $imageStatement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($images as $image) {
            $filePath = "../uploads/images/" . $image['image_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete images from the database
        $deleteQuery = "DELETE FROM employee_images WHERE employee_ssn = :ssn";
        $deleteStatement = $connection->prepare($deleteQuery);
        $deleteStatement->execute(['ssn' => $ssn]);
    }
     // Fetch departments data
     public function getDepartments()
     {
         $connection = $this->getConnection();
         try {
             $stmt = $connection->query("SELECT * FROM department");
             return $stmt->fetchAll(PDO::FETCH_ASSOC);
         } catch (PDOException $e) {
             $this->errors[] = "Error fetching departments: " . $e->getMessage();
             return [];
         }
     }
 
     // Fetch supervisors' SSNs
     public function getSupervisors()
     {
         $connection = $this->getConnection();
         try {
             $stmt = $connection->query("SELECT ssn, fname, lname FROM employee");
             return $stmt->fetchAll(PDO::FETCH_ASSOC);
         } catch (PDOException $e) {
             $this->errors[] = "Error fetching supervisors: " . $e->getMessage();
             return [];
         }
     }
 }

?>
