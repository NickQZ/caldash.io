<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Basic validation
    if (empty($email) || empty($password)) {

        $error = "Please enter your email and password.";
    } else {

        // Find user by email
        $sql = "SELECT user_id, first_name, password_hash FROM `users` WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // Check password
            if (password_verify($password, $user["password_hash"])) {

                // Login successful
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["first_name"] = $user["first_name"];

                // Send user to dashboard
                header("Location: dashboard.php");
                exit();
            } else {

                $error = "Incorrect email or password.";
            }
        } else {

            $error = "Incorrect email or password.";
        }

        $stmt->close();
    }
}

?>

<?php include "includes/header.php"; ?>

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">

                <h1 class="fw-bold">
                    CalDash.io
                </h1>

                <p class="text-muted">
                    Sign in to your account
                </p>

            </div>

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h3 class="fw-bold mb-4">
                        Login
                    </h3>

                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST" action="login.php">

                        <!-- Email -->

                        <div class="mb-3">

                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required>

                        </div>


                        <!-- Password -->

                        <div class="mb-4">

                            <label for="password" class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required>

                        </div>


                        <!-- Login Button -->

                        <button
                            type="submit"
                            class="btn btn-dark w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>

            <div class="text-center mt-3">

                <p class="text-muted">
                    Don't have an account?
                    <a href="register.php">
                        Register
                    </a>
                </p>

            </div>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>