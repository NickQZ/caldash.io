<?php

session_start();

require_once "config/database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form data
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";


    // -----------------------------
    // Basic validation
    // -----------------------------

    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $error = "Please complete all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {

        $error = "Password must be at least 8 characters long.";
    } else {

        // -----------------------------
        // Check if email already exists
        // -----------------------------

        $sql = "SELECT user_id FROM users WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $error = "An account with this email already exists.";
        } else {

            // -----------------------------
            // Hash password
            // -----------------------------

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            // -----------------------------
            // Insert user
            // -----------------------------

            $sql = "
                INSERT INTO `users`
                (
                    first_name,
                    last_name,
                    email,
                    password_hash
                )
                VALUES (?, ?, ?, ?)
            ";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssss",
                $first_name,
                $last_name,
                $email,
                $password_hash
            );


            if ($stmt->execute()) {

                $success = "Account created successfully! You can now log in.";
            } else {

                $error = "Something went wrong while creating your account.";
            }
        }

        $stmt->close();
    }
}

?>

<?php include "includes/header.php"; ?>

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-6 col-lg-5">

            <!-- Logo -->

            <div class="text-center mb-4">

                <h1 class="fw-bold">
                    CalDash.io
                </h1>

                <p class="text-muted">
                    Create your account
                </p>

            </div>


            <!-- Registration Card -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h3 class="fw-bold mb-4">
                        Register
                    </h3>


                    <!-- Error Message -->

                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>

                    <?php endif; ?>


                    <!-- Success Message -->

                    <?php if (!empty($success)): ?>

                        <div class="alert alert-success">

                            <?= htmlspecialchars($success); ?>

                            <div class="mt-2">

                                <a
                                    href="login.php"
                                    class="btn btn-success btn-sm">
                                    Go to Login
                                </a>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- Registration Form -->

                    <form method="POST" action="register.php">

                        <!-- First Name -->

                        <div class="mb-3">

                            <label
                                for="first_name"
                                class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                class="form-control"
                                maxlength="50"
                                required>

                        </div>


                        <!-- Last Name -->

                        <div class="mb-3">

                            <label
                                for="last_name"
                                class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                class="form-control"
                                maxlength="50"
                                required>

                        </div>


                        <!-- Email -->

                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                maxlength="191"
                                placeholder="example@email.com"
                                required>

                        </div>


                        <!-- Password -->

                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                minlength="8"
                                required>

                            <div class="form-text">
                                Password must be at least 8 characters.
                            </div>

                        </div>


                        <!-- Confirm Password -->

                        <div class="mb-4">

                            <label
                                for="confirm_password"
                                class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                class="form-control"
                                minlength="8"
                                required>

                        </div>


                        <!-- Register Button -->

                        <button
                            type="submit"
                            class="btn btn-dark w-100">
                            Create Account
                        </button>

                    </form>

                </div>

            </div>


            <!-- Login Link -->

            <div class="text-center mt-3">

                <p class="text-muted">

                    Already have an account?

                    <a href="login.php">
                        Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>