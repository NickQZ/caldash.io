<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container py-5">

    <div class="mb-4">

        <h1 class="fw-bold">
            Dashboard
        </h1>

        <p class="text-muted">
            Here's your nutrition summary for today.
        </p>

    </div>


    <!-- Nutrition Summary -->

    <div class="row g-4">

        <div class="col-md-3">

            <div class="card dashboard-card p-3">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Calories
                    </p>

                    <div class="nutrition-value">
                        0
                    </div>

                    <small class="text-muted">
                        kcal
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card dashboard-card p-3">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Protein
                    </p>

                    <div class="nutrition-value">
                        0g
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card dashboard-card p-3">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Carbs
                    </p>

                    <div class="nutrition-value">
                        0g
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card dashboard-card p-3">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Fat
                    </p>

                    <div class="nutrition-value">
                        0g
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Add Meal -->

    <div class="card dashboard-card mt-5">

        <div class="card-body p-4">

            <h4 class="fw-bold">
                Add a meal
            </h4>

            <p class="text-muted">
                Record what you've eaten today.
            </p>

            <a href="add_meal.php" class="btn btn-dark">
                Add Meal
            </a>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>