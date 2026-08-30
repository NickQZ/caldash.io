<?php
session_start();
require_once "config/database.php";

global $conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

include "includes/header.php";
include "includes/navbar.php";

$sql = "SELECT
            COALESCE(SUM(f.calories * m.quantity_consumed), 0) AS total_calories,
            COALESCE(SUM(f.carbohydrates * m.quantity_consumed), 0) AS total_carbohydrates,
            COALESCE(SUM(f.fat * m.quantity_consumed), 0) AS total_fat,
            COALESCE(SUM(f.protein * m.quantity_consumed), 0) AS total_protein
        FROM meal_entry m
        JOIN food f ON m.food_id = f.food_id
        WHERE m.user_id = ?
        AND DATE(m.meal_date) = CURDATE()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$nutrition = $result->fetch_assoc();

$total_calories = $nutrition['total_calories'];
$total_carbohydrates = $nutrition['total_carbohydrates'];
$total_fat = $nutrition['total_fat'];
$total_protein = $nutrition['total_protein'];

$sql = "SELECT
            m.meal_type,
            m.quantity_consumed,
            f.food_name,
            f.serving_size,
            f.serving_unit,
            f.calories,
            f.carbohydrates,
            f.fat,
            f.protein
        FROM meal_entry m
        JOIN food f ON m.food_id = f.food_id
        WHERE m.user_id = ?
        AND DATE(m.meal_date) = CURDATE()
        ORDER BY m.meal_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$meals = [];

while ($meal_row = $result->fetch_assoc()) {
    $meals[] = $meal_row;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CalDash</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<div class="container py-5">

    <div class="dashboard-heading mb-4">
        <h2>Today's Nutrition</h2>
        <p><?php echo date("l, d F Y"); ?></p>
    </div>

    <div class="card mb-4">
        <div class="card-body px-3 d-flex align-items-center justify-content-center justify-content-md-start">
            <h2 class="mb-0">
                Total Calories: <?php echo round($total_calories); ?> kcal
            </h2>
        </div>
    </div>

    <div class="row g-3 mb-5">

        <div class="col-4">
            <div class="card text-center h-100">
                <div class="card-body py-3 px-2">
                    <h6 class="text-muted">Carbs</h6>
                    <h4><?php echo round($total_carbohydrates); ?>g</h4>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card text-center h-100">
                <div class="card-body py-3 px-2">
                    <h6 class="text-muted">Fat</h6>
                    <h4><?php echo round($total_fat); ?>g</h4>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card text-center h-100">
                <div class="card-body py-3 px-2">
                    <h6 class="text-muted">Protein</h6>
                    <h4><?php echo round($total_protein); ?>g</h4>
                </div>
            </div>
        </div>

    </div>

    <div class="mb-4">

        <h4 class="text-center text-md-start">Today's Meals</h4>

        <div class="row g-3">

            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Breakfast</h5>

                        <?php
                        $breakfast_found = false;

                        foreach ($meals as $meal_row):
                            if ($meal_row['meal_type'] === 'Breakfast'):
                                $breakfast_found = true;
                        ?>

                                <p class="mb-1">
                                    <?php echo htmlspecialchars($meal_row['food_name']); ?>
                                </p>

                                <p class="mb-3">
                                    <?php echo round($meal_row['calories'] * $meal_row['quantity_consumed']); ?> kcal |
                                    <?php echo round($meal_row['carbohydrates'] * $meal_row['quantity_consumed']); ?>g carbs |
                                    <?php echo round($meal_row['fat'] * $meal_row['quantity_consumed']); ?>g fat |
                                    <?php echo round($meal_row['protein'] * $meal_row['quantity_consumed']); ?>g protein
                                </p>

                            <?php
                            endif;
                        endforeach;

                        if (!$breakfast_found):
                            ?>

                            <p class="text-muted">No meal logged</p>

                        <?php endif; ?>

                        <a href="add_meal.php?meal=breakfast" class="btn btn-dark">
                            Log Breakfast
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Lunch</h5>

                        <?php
                        $lunch_found = false;

                        foreach ($meals as $meal_row):
                            if ($meal_row['meal_type'] === 'Lunch'):
                                $lunch_found = true;
                        ?>

                                <p class="mb-1">
                                    <?php echo htmlspecialchars($meal_row['food_name']); ?>
                                </p>

                                <p class="mb-3">
                                    <?php echo round($meal_row['calories'] * $meal_row['quantity_consumed']); ?> kcal |
                                    <?php echo round($meal_row['carbohydrates'] * $meal_row['quantity_consumed']); ?>g carbs |
                                    <?php echo round($meal_row['fat'] * $meal_row['quantity_consumed']); ?>g fat |
                                    <?php echo round($meal_row['protein'] * $meal_row['quantity_consumed']); ?>g protein
                                </p>

                            <?php
                            endif;
                        endforeach;

                        if (!$lunch_found):
                            ?>

                            <p class="text-muted">No meal logged</p>

                        <?php endif; ?>

                        <a href="add_meal.php?meal=lunch" class="btn btn-dark">
                            Log Lunch
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Dinner</h5>

                        <?php
                        $dinner_found = false;

                        foreach ($meals as $meal_row):
                            if ($meal_row['meal_type'] === 'Dinner'):
                                $dinner_found = true;
                        ?>

                                <p class="mb-1">
                                    <?php echo htmlspecialchars($meal_row['food_name']); ?>
                                </p>

                                <p class="mb-3">
                                    <?php echo round($meal_row['calories'] * $meal_row['quantity_consumed']); ?> kcal |
                                    <?php echo round($meal_row['carbohydrates'] * $meal_row['quantity_consumed']); ?>g carbs |
                                    <?php echo round($meal_row['fat'] * $meal_row['quantity_consumed']); ?>g fat |
                                    <?php echo round($meal_row['protein'] * $meal_row['quantity_consumed']); ?>g protein
                                </p>

                            <?php
                            endif;
                        endforeach;

                        if (!$dinner_found):
                            ?>

                            <p class="text-muted">No meal logged</p>

                        <?php endif; ?>

                        <a href="add_meal.php?meal=dinner" class="btn btn-dark">
                            Log Dinner
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center text-md-start">
                        <h5>Snacks</h5>

                        <?php
                        $snack_found = false;

                        foreach ($meals as $meal_row):
                            if ($meal_row['meal_type'] === 'Snack'):
                                $snack_found = true;
                        ?>

                                <p class="mb-1">
                                    <?php echo htmlspecialchars($meal_row['food_name']); ?>
                                </p>

                                <p class="mb-3">
                                    <?php echo round($meal_row['calories'] * $meal_row['quantity_consumed']); ?> kcal |
                                    <?php echo round($meal_row['carbohydrates'] * $meal_row['quantity_consumed']); ?>g carbs |
                                    <?php echo round($meal_row['fat'] * $meal_row['quantity_consumed']); ?>g fat |
                                    <?php echo round($meal_row['protein'] * $meal_row['quantity_consumed']); ?>g protein
                                </p>

                            <?php
                            endif;
                        endforeach;

                        if (!$snack_found):
                            ?>

                            <p class="text-muted">No meal logged</p>

                        <?php endif; ?>

                        <a href="add_meal.php?meal=snacks" class="btn btn-dark">
                            Log Snacks
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

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