<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: meal_history.php");
    exit();
}

$meal_entry_id = (int) $_GET['id'];

$error = "";

/*
 * Get the selected meal.
 * The user_id check ensures users can only edit
 * their own meals.
 */
$sql = "
    SELECT
        m.meal_entry_id,
        m.meal_type,
        m.quantity_consumed,
        m.meal_date,
        f.food_name
    FROM meal_entry m
    JOIN food f ON m.food_id = f.food_id
    WHERE m.meal_entry_id = ?
    AND m.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $meal_entry_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$meal = $result->fetch_assoc();

$stmt->close();

if (!$meal) {
    header("Location: meal_history.php");
    exit();
}


/*
 * Handle form submission
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $meal_type = $_POST['meal_type'] ?? "";
    $quantity_consumed = $_POST['quantity_consumed'] ?? "";

    $allowed_meal_types = [
        "Breakfast",
        "Lunch",
        "Dinner",
        "Snack"
    ];

    if (!in_array($meal_type, $allowed_meal_types)) {

        $error = "Please select a valid meal type.";
    } elseif (!is_numeric($quantity_consumed) || $quantity_consumed <= 0) {

        $error = "Please enter a valid quantity.";
    } else {

        $quantity_consumed = (float) $quantity_consumed;

        $sql = "
            UPDATE meal_entry
            SET meal_type = ?,
                quantity_consumed = ?
            WHERE meal_entry_id = ?
            AND user_id = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sdii",
            $meal_type,
            $quantity_consumed,
            $meal_entry_id,
            $user_id
        );

        $stmt->execute();

        $stmt->close();

        header("Location: meal_history.php");
        exit();
    }
}

include "includes/header.php";
include "includes/navbar.php";

?>

<div class="container py-5">

    <div class="dashboard-heading mb-4">
        <h2>Edit Meal</h2>
        <p>
            Update your logged meal details.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h5 class="mb-4">
                <?php echo htmlspecialchars($meal['food_name']); ?>
            </h5>

            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <!-- Meal Type -->
                <div class="mb-3">

                    <label for="meal_type" class="form-label">
                        Meal Type
                    </label>

                    <select
                        name="meal_type"
                        id="meal_type"
                        class="form-select"
                        required>

                        <option value="Breakfast"
                            <?php echo $meal['meal_type'] === 'Breakfast' ? 'selected' : ''; ?>>
                            Breakfast
                        </option>

                        <option value="Lunch"
                            <?php echo $meal['meal_type'] === 'Lunch' ? 'selected' : ''; ?>>
                            Lunch
                        </option>

                        <option value="Dinner"
                            <?php echo $meal['meal_type'] === 'Dinner' ? 'selected' : ''; ?>>
                            Dinner
                        </option>

                        <option value="Snack"
                            <?php echo $meal['meal_type'] === 'Snack' ? 'selected' : ''; ?>>
                            Snack
                        </option>

                    </select>

                </div>


                <!-- Quantity -->
                <div class="mb-4">

                    <label for="quantity_consumed" class="form-label">
                        Quantity
                    </label>

                    <input
                        type="number"
                        name="quantity_consumed"
                        id="quantity_consumed"
                        class="form-control"
                        value="<?php echo htmlspecialchars($meal['quantity_consumed']); ?>"
                        min="0.01"
                        step="0.01"
                        required>

                </div>


                <!-- Buttons -->
                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-dark">
                        Save Changes
                    </button>

                    <a
                        href="meal_history.php"
                        class="btn btn-outline-dark">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?php

include "includes/footer.php";

?>