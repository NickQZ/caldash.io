<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

include "includes/header.php";
include "includes/navbar.php";


// Get today's nutrition totals
$sql = "
    SELECT
        COALESCE(SUM(f.calories * m.quantity_consumed), 0) AS total_calories,
        COALESCE(SUM(f.carbohydrates * m.quantity_consumed), 0) AS total_carbohydrates,
        COALESCE(SUM(f.fat * m.quantity_consumed), 0) AS total_fat,
        COALESCE(SUM(f.protein * m.quantity_consumed), 0) AS total_protein
    FROM meal_entry m
    JOIN food f ON m.food_id = f.food_id
    WHERE m.user_id = ?
    AND DATE(m.meal_date) = CURDATE()
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$nutrition = $stmt->get_result()->fetch_assoc();

$total_calories = $nutrition['total_calories'];
$total_carbohydrates = $nutrition['total_carbohydrates'];
$total_fat = $nutrition['total_fat'];
$total_protein = $nutrition['total_protein'];


// Get today's meals
$sql = "
    SELECT
        m.meal_type,
        m.quantity_consumed,
        f.food_name,
        f.calories,
        f.carbohydrates,
        f.fat,
        f.protein
    FROM meal_entry m
    JOIN food f ON m.food_id = f.food_id
    WHERE m.user_id = ?
    AND DATE(m.meal_date) = CURDATE()
    ORDER BY m.meal_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$meals = [];

while ($meal = $result->fetch_assoc()) {
    $meals[] = $meal;
}


// Meal types used by the dashboard
$meal_types = [
    'Breakfast',
    'Lunch',
    'Dinner',
    'Snack'
];

?>

<div class="container py-5 dashboard">

    <!-- Dashboard Heading -->
    <div class="dashboard-heading mb-4">
        <h2>Today's Nutrition</h2>
        <p><?php echo date("l, d F Y"); ?></p>
    </div>


    <!-- Total Calories -->
    <div class="card mb-4 calories-card">
        <div class="card-body px-3 d-flex align-items-center justify-content-center justify-content-md-start">
            <h2 class="mb-0">
                Total Calories: <?php echo round($total_calories); ?> kcal
            </h2>
        </div>
    </div>


    <!-- Nutrition Summary -->
    <div class="row g-3 mb-5">

        <div class="col-4">
            <div class="card nutrition-card text-center carbs">
                <div class="card-body py-3 px-2">
                    <h6>Carbs</h6>
                    <h4><?php echo round($total_carbohydrates); ?>g</h4>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card nutrition-card text-center fat">
                <div class="card-body py-3 px-2">
                    <h6>Fat</h6>
                    <h4><?php echo round($total_fat); ?>g</h4>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card nutrition-card text-center protein">
                <div class="card-body py-3 px-2">
                    <h6>Protein</h6>
                    <h4><?php echo round($total_protein); ?>g</h4>
                </div>
            </div>
        </div>

    </div>


    <!-- Today's Meals -->
    <h4 class="meals-title">Today's Meals</h4>

    <div class="row g-3">

        <?php foreach ($meal_types as $meal_type): ?>

            <?php
            $meal_found = false;
            $meal_link = strtolower($meal_type);

            if ($meal_type === 'Snack') {
                $meal_link = 'snacks';
            }
            ?>

            <div class="col-6 col-md-3">

                <div class="card meal-card">

                    <div class="card-body text-center text-md-start">

                        <h5>
                            <?php echo $meal_type === 'Snack' ? 'Snacks' : $meal_type; ?>
                        </h5>


                        <!-- Success Message -->
                        <?php if (
                            isset($_SESSION['success_meal']) &&
                            $_SESSION['success_meal'] === $meal_type
                        ): ?>

                            <div class="meal-success">
                                ✓ <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                            </div>

                        <?php endif; ?>


                        <!-- Logged Meals -->
                        <?php foreach ($meals as $meal_row): ?>

                            <?php if ($meal_row['meal_type'] === $meal_type): ?>

                                <?php $meal_found = true; ?>

                                <p class="food-name">
                                    <?php echo htmlspecialchars($meal_row['food_name']); ?>
                                </p>

                                <p class="nutrition-info">
                                    <?php echo round($meal_row['calories'] * $meal_row['quantity_consumed']); ?> kcal |
                                    <?php echo round($meal_row['carbohydrates'] * $meal_row['quantity_consumed']); ?>g carbs |
                                    <?php echo round($meal_row['fat'] * $meal_row['quantity_consumed']); ?>g fat |
                                    <?php echo round($meal_row['protein'] * $meal_row['quantity_consumed']); ?>g protein
                                </p>

                            <?php endif; ?>

                        <?php endforeach; ?>

                        <!-- No Meal Message -->
                        <?php if (!$meal_found): ?>

                            <p class="text-muted">No meal logged</p>

                        <?php endif; ?>

                        <!-- Add Meal Button -->
                        <a
                            href="add_meal.php?meal=<?php echo $meal_link; ?>"
                            class="btn btn-dark">
                            Log <?php echo $meal_type === 'Snack' ? 'Snacks' : $meal_type; ?>
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

        <!-- Navigation Buttons -->
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

</div>



<!-- Success Message Fade -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const message = document.querySelector(".meal-success");

        if (message) {

            setTimeout(function() {

                message.style.transition = "opacity 0.3s ease";
                message.style.opacity = "0";

                setTimeout(function() {
                    message.remove();
                }, 300);

            }, 2500);
        }

    });
</script>

<?php

unset($_SESSION['success_message']);
unset($_SESSION['success_meal']);

include "includes/footer.php";
