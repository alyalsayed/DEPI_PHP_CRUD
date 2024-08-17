<?php
require_once "./classes/Employee.php";  

$employee = new Employee();

$employees = $employee->index();


include_once "../includes/header.php"; 
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
                                        <a href="#" class="text-danger delete-btn" data-ssn="<?php echo $ssn ?>" data-toggle="modal" data-target="#confirmDeleteModal">delete</a>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this employee? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="get" action="delete.php">
                    <input type="hidden" name="ssn" id="employeeSSN">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once "../includes/footer.php";
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const ssn = this.getAttribute('data-ssn');
                document.getElementById('employeeSSN').value = ssn;
            });
        });
    });
</script>
