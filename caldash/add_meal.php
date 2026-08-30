<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$meal = $_GET['meal'] ?? $_POST['meal'] ?? '';

// Convert URL value to database ENUM value
$meal_types = [
    'breakfast' => 'Breakfast',
    'lunch' => 'Lunch',
    'dinner' => 'Dinner',
    'snacks' => 'Snack'
];

if (!isset($meal_types[$meal])) {
    header("Location: dashboard.php");
    exit();
}

$meal_type = $meal_types[$meal];


// Add meal to database
if (isset($_POST['add_meal'])) {

    $food_id = $_POST['food_id'];
    $quantity = $_POST['quantity'];

    $sql = "
        INSERT INTO MEAL_ENTRY
        (
            user_id,
            food_id,
            meal_type,
            quantity_consumed
        )
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iisd",
        $user_id,
        $food_id,
        $meal_type,
        $quantity
    );

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}


// Search for food
$search = trim($_GET['search'] ?? '');

$foods = [];

if ($search !== '') {

    $sql = "
        SELECT
            food_id,
            food_name,
            serving_size,
            serving_unit,
            calories,
            carbohydrates,
            fat,
            protein
        FROM FOOD
        WHERE food_name LIKE ?
        ORDER BY food_name ASC
    ";

    $stmt = $conn->prepare($sql);

    $search_term = "%" . $search . "%";

    $stmt->bind_param("s", $search_term);

    $stmt->execute();

    $result = $stmt->get_result();

    while ($food = $result->fetch_assoc()) {
        $foods[] = $food;
    }
}

?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container py-5">

    <!-- Page Heading -->
    <div class="text-center text-md-start mb-4">

        <h2>
            Log <?php echo htmlspecialchars($meal_type); ?>
        </h2>

        <p class="text-muted mb-0">
            Search for a food to add to your meal.
        </p>

    </div>


    <!-- Food Search -->
    <form method="GET" action="add_meal.php" class="mb-4">

        <input
            type="hidden"
            name="meal"
            value="<?php echo htmlspecialchars($meal); ?>">

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search food..."
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">

            <button
                class="btn btn-dark"
                type="submit">
                Search
            </button>

        </div>

    </form>


    <!-- Search Results -->
    <?php if ($search !== ''): ?>
        <h4 class="mb-3"> Search Results </h4>

        <?php if (empty($foods)): ?>

            <p class="text-muted"> Sorry no food found try again. </p>

        <?php else: ?>

            <div class="row g-3">
                <?php foreach ($foods as $food): ?>
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5>
                                    <?php echo htmlspecialchars($food['food_name']); ?>
                                </h5>

                                <p class="text-muted mb-2">
                                    <?php echo $food['serving_size']; ?>
                                    <?php echo htmlspecialchars($food['serving_unit']); ?>
                                </p>

                                <p class="mb-3">
                                    <?php echo $food['calories']; ?> kcal |
                                    <?php echo $food['carbohydrates']; ?>g carbs |
                                    <?php echo $food['fat']; ?>g fat |
                                    <?php echo $food['protein']; ?>g protein
                                </p>
                                <!-- Add Food Form -->
                                <form method="POST" action="add_meal.php">

                                    <input
                                        type="hidden"
                                        name="food_id"
                                        value="<?php echo $food['food_id']; ?>">

                                    <input
                                        type="hidden"
                                        name="meal"
                                        value="<?php echo htmlspecialchars($meal); ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Quantity (Servings)</label>
                                        <input
                                            type="number"
                                            name="quantity"
                                            class="form-control"
                                            value="1"
                                            min="0.01"
                                            step="0.01"
                                            required>
                                    </div>
                                    <button
                                        type="submit"
                                        name="add_meal"
                                        class="btn btn-dark">
                                        Add to <?php echo htmlspecialchars($meal_type); ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>


    <!-- Dashboard Button -->
    <div class="row g-3 mt-4">
        <div class="col-12">
            <a href="dashboard.php" class="btn btn-dark w-100">Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>