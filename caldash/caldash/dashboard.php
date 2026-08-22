<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container py-5">

    <!-- Page Heading -->
    <div class="text-center text-md-start mb-4">
        <h2>Today's Nutrition</h2>
        <p class="text-muted mb-0">
            <?php echo date("d/m/Y"); ?>
        </p>
    </div>

    <!-- Calories -->
    <div class="card mb-4">
        <div class="card-body px-3 d-flex align-items-center justify-content-center justify-content-md-start">
            <h2 class="mb-0">Total Calories: 1,850 kcal</h2>
        </div>
    </div>

    <!-- Macronutrients -->
    <div class="row g-3 mb-5">

        <!-- Carbs -->
        <div class="col-4">
            <div class="card text-center h-100">
                <div class="card-body py-3 px-2">
                    <h6 class="text-muted">Carbs</h6>
                    <h4>250g</h4>
                </div>
            </div>
        </div>

        <!-- Fat -->
        <div class="col-4">
            <div class="card text-center h-100">
                <div class="card-body py-3 px-2">
                    <h6 class="text-muted">Fat</h6>
                    <h4>60g</h4>
                </div>
            </div>
        </div>

        <!-- Protein -->
        <div class="col-4">
            <div class="card text-center h-100">
                <div class="card-body py-3 px-2">
                    <h6 class="text-muted">Protein</h6>
                    <h4>130g</h4>
                </div>
            </div>
        </div>

    </div>

    <!-- Today's Meals -->
    <div class="mb-4">
        <h4 class="text-center text-md-start">Today's Meals</h4>

        <div class="row g-3">

            <!-- Breakfast -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Breakfast</h5>
                        <p class="text-muted">No meal logged</p>
                        <a href="add_meal.php?meal=breakfast" class="btn btn-dark">
                            Log Breakfast
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lunch -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Lunch</h5>
                        <p class="text-muted">No meal logged</p>
                        <a href="add_meal.php?meal=lunch" class="btn btn-dark">
                            Log Lunch
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dinner -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Dinner</h5>
                        <p class="text-muted">No meal logged</p>
                        <a href="add_meal.php?meal=dinner" class="btn btn-dark">
                            Log Dinner
                        </a>
                    </div>
                </div>
            </div>

            <!-- Snacks -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Snacks</h5>
                        <p class="text-muted">No snacks logged</p>
                        <a href="add_meal.php?meal=snacks" class="btn btn-dark">
                            Log Snacks
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Dashboard Actions -->
    <div class="row g-3 mt-4">

        <div class="col-md-6">
            <a href="meal_history.php" class="btn btn-outline-dark w-100">
                Meal History
            </a>
        </div>

        <div class="col-md-6">
            <a href="dashboard.php" class="btn btn-dark w-100">
                Dashboard
            </a>
        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>