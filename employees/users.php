<?php
require_once "../includes/connection.php"; // Connect to the database
// Toggle admin status if action is triggered
if (isset($_GET['toggle_admin']) && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    
    // Check if the current user is changing their own admin status
    if (isset($_SESSION['id']) && $_SESSION['id'] == $userId) {
        $stmt = $connection->prepare("UPDATE users SET is_admin = 1 - is_admin WHERE id = ?");
        $stmt->execute([$userId]);

        // Fetch the updated admin status for the current user
        $stmt = $connection->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update session variable based on the new status
        $_SESSION['is_admin'] = $user['is_admin'];

        // If the user is no longer an admin, redirect to the login page
        if ($_SESSION['is_admin'] == 0) {
            session_unset();
            session_destroy();
            session_regenerate_id();
            header("Location: ../login.php"); // Redirect to login page
            exit();
        }
    } else {
        // Update the admin status for other users
        $stmt = $connection->prepare("UPDATE users SET is_admin = 1 - is_admin WHERE id = ?");
        $stmt->execute([$userId]);
    }

    header("Location: users.php"); // Redirect to avoid form resubmission
    exit();
}


// Fetch all users from the database
$query = "
    SELECT 
        u.id, 
        u.email, 
        u.is_admin, 
        MAX(ua.last_login) AS last_login, 
        MAX(ua.last_activity) AS last_activity
    FROM users u
    LEFT JOIN users_activity ua ON u.id = ua.user_id
    GROUP BY u.id, u.email, u.is_admin
";
$result = $connection->query($query);
$users = $result->fetchAll(PDO::FETCH_ASSOC);
$result = $connection->query($query);
$users = $result->fetchAll(PDO::FETCH_ASSOC);

// Fetch active users (those who have logged in)
$queryActive = "
    SELECT 
        u.id, 
        u.email, 
        u.is_admin, 
        MAX(ua.last_login) AS last_login, 
        MAX(ua.last_activity) AS last_activity
    FROM users u
    JOIN users_activity ua ON u.id = ua.user_id
    GROUP BY u.id, u.email, u.is_admin
";
$resultActive = $connection->query($queryActive);
$activeUsers = $resultActive->fetchAll(PDO::FETCH_ASSOC);

include_once "../includes/header.php"; // Include the header
?>

<div class="content">
    <div class="animated fadeIn">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">All Users</strong>
                </div>

                <div class="card-body">
                    <?php if (!empty($users)) { ?>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">User ID</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></td>
                                        <td>
                                            <a href="users.php?toggle_admin=1&user_id=<?php echo $user['id']; ?>" class="btn btn-primary">
                                                <?php echo $user['is_admin'] ? 'Revoke Admin' : 'Make Admin'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info">No users found.</div>
                    <?php } ?>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header">
                    <strong class="card-title">Active Users</strong>
                </div>

                <div class="card-body">
                    <?php if (!empty($activeUsers)) { ?>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">User ID</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Last Login</th>
                                    <th scope="col">Last Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeUsers as $user) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></td>
                                        <td><?php echo htmlspecialchars($user['last_login']); ?></td>
                                        <td><?php echo htmlspecialchars($user['last_activity']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info">No active users at the moment.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div><!-- .animated -->

<?php
include_once "../includes/footer.php"; // Include the footer
?>
