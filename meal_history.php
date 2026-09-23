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

// Get meal history for the logged in user
$sql = "
        SELECT
            m.meal_entry_id,
            m.meal_type,
            m.quantity_consumed,
            m.meal_date,
            f.food_name,
            f.calories,
            f.carbohydrates,
            f.fat,
            f.protein
        FROM meal_entry m
        JOIN food f ON m.food_id = f.food_id
        WHERE m.user_id = ?
        ORDER BY m.meal_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$meals = [];

while ($meal = $result->fetch_assoc()) {
    $meals[] = $meal;
}

?>

<div class="container py-5">

    <!-- Page Heading -->
    <div class="dashboard-heading mb-4">

        <h2>Meal History</h2>

        <p>
            View your previously logged meals.
        </p>

    </div>


    <?php if (empty($meals)): ?>

        <!-- No Meals -->
        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <h4>No meals logged yet</h4>

                <p class="text-muted">
                    Your logged meals will appear here.
                </p>

                <a href="dashboard.php" class="btn btn-dark">
                    Back to Dashboard
                </a>

            </div>

        </div>


    <?php else: ?>

        <?php

        $current_date = null;

        foreach ($meals as $meal):

            $meal_date = date(
                "Y-m-d",
                strtotime($meal['meal_date'])
            );


            // Start a new date section
            if ($meal_date !== $current_date):

                if ($current_date !== null):
        ?>

</div>

<?php endif; ?>


<!-- Date Heading -->
<div class="meal-history-date mb-3 mt-4">

    <h4>
        <?php
                echo date(
                    "l, d F Y",
                    strtotime($meal['meal_date'])
                );
        ?>
    </h4>

</div>


<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

    <?php

                $current_date = $meal_date;

            endif;

    ?>


    <!-- Meal -->
    <div class="meal-history-item py-3 border-bottom">

        <div class="row align-items-center">

            <!-- Meal Information -->
            <div class="col-md-3">

                <span class="badge text-bg-success mb-2">
                    <?php
                    echo htmlspecialchars(
                        $meal['meal_type']
                    );
                    ?>
                </span>

                <h5 class="mb-0">
                    <?php
                    echo htmlspecialchars(
                        $meal['food_name']
                    );
                    ?>
                </h5>

            </div>


            <!-- Quantity -->
            <div class="col-md-2">

                <small class="text-muted">
                    Quantity
                </small>

                <div>
                    <?php
                    echo htmlspecialchars(
                        $meal['quantity_consumed']
                    );
                    ?>
                </div>

            </div>


            <!-- Calories -->
            <div class="col-md-2">

                <small class="text-muted">
                    Calories
                </small>

                <div>
                    <?php
                    echo round(
                        $meal['calories']
                            * $meal['quantity_consumed']
                    );
                    ?>
                    kcal
                </div>

            </div>


            <!-- Macros -->
            <div class="col-md-3">

                <small class="text-muted">
                    Nutrition
                </small>

                <div class="nutrition-info">

                    <?php
                    echo round(
                        $meal['carbohydrates']
                            * $meal['quantity_consumed']
                    );
                    ?>
                    g carbs |

                    <?php
                    echo round(
                        $meal['fat']
                            * $meal['quantity_consumed']
                    );
                    ?>
                    g fat |

                    <?php
                    echo round(
                        $meal['protein']
                            * $meal['quantity_consumed']
                    );
                    ?>
                    g protein

                </div>

            </div>
            <!-- Actions -->
            <div class="col-md-2 text-md-end mt-3 mt-md-0">

                <!-- Edit -->
                <a
                    href="edit_meal.php?id=<?php echo (int)$meal['meal_entry_id']; ?>"
                    class="btn btn-sm btn-outline-secondary rounded-circle p-0 flex-shrink-0"
                    style="width: 30px; height: 30px;"
                    title="Edit meal"
                    aria-label="Edit meal">
                    <i class="bi bi-pencil"></i>
                </a>

                <!-- Delete -->
                <a
                    href="delete_meal.php?id=<?php echo (int)$meal['meal_entry_id']; ?>"
                    class="btn btn-sm btn-outline-secondary rounded-circle p-0 flex-shrink-0"
                    style="width: 30px; height: 30px;"
                    onclick="return confirm('Are you sure you want to delete this meal?');"
                    title="Delete meal"
                    aria-label="Delete meal">
                    <i class="bi bi-trash"></i>
                </a>

            </div>
        </div>
    </div>


<?php endforeach; ?>


    </div>

    <!-- Navigation Buttons -->
    <div class="row g-3 mt-4">

        <div class="col-md-6">
            <a href="meal_history.php" class="btn btn-dark w-100">
                Meal History
            </a>
        </div>

        <div class="col-md-6">
            <a href="dashboard.php" class="btn btn-outline-dark w-100">
                Dashboard
            </a>
        </div>

    </div>
</div>

</div>
<?php endif; ?>

</div>

<?php

include "includes/footer.php";

?>