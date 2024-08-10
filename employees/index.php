<?php
require_once "../includes/connection.php"; // Connect to the database

// Fetch all employees from the database
$result = $connection->query('SELECT fname, lname, ssn, dno, salary FROM employee');
$employees = $result->fetchAll(PDO::FETCH_ASSOC);


include_once "../includes/header.php"; // Include the aside.php file
?>

<!-- Header -->
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>All Employees</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Employees</a></li>
                            <li><a href="#">Add Employee</a></li>
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
                <div class="card-header">
                    <strong class="card-title">Employees</strong>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">SSN</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee) {
                                $ssn = $employee['ssn'];
                            ?>
                                <tr>
                                    <th scope='row'> <?php echo $employee['fname'] . ' ' . $employee['lname']; ?></th>
                                    <td><?php echo $employee['ssn']; ?></td>
                                   
                                    <td>
                                        <a href="show.php?ssn=<?php echo $ssn ?>" style="color:blue">show</a>
                                        <a href="edit.php?ssn=<?php echo $ssn ?>" style="color:green">edit</a>
                                        <a href="delete.php?ssn=<?php echo $ssn ?>" style="color:red">delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
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
