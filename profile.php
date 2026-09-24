<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT first_name, last_name, email, created_at
    FROM users
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

include "includes/header.php";
include "includes/navbar.php";

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <!-- Page Heading -->
                    <div class="text-center mb-4">
                        <h2 class="fw-semibold mb-1">
                            My Profile
                        </h2>
                        <p class="text-muted mb-0">
                            Manage your CalDash account
                        </p>
                    </div>

                    <!-- Profile Photo -->
                    <div class="text-center mb-4">
                        <div
                            class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto"
                            style="width: 130px; height: 130px;">
                            <i class="bi bi-person text-success" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- User Name -->
                    <div class="text-center mb-4">
                        <h3 class="fw-semibold mb-1">
                            <?php
                            echo htmlspecialchars(
                                $user['first_name'] . ' ' . $user['last_name']
                            );
                            ?>
                        </h3>
                        <p class="text-muted mb-0">
                            Member since
                            <?php
                            echo date(
                                "F Y",
                                strtotime($user['created_at'])
                            );
                            ?>
                        </p>
                    </div>

                    <!-- Account Information -->
                    <div class="mb-4">
                        <h5 class="fw-semibold mb-3">
                            Account Information
                        </h5>

                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-envelope me-2 text-success"></i>
                                    <strong>Email</strong>
                                </div>
                                <span class="text-muted">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 center mx-auto">
                        <a href="dashboard.php" class="btn btn-dark w-100">
                            Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
```