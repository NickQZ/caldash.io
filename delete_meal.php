<?php

session_start();

require_once "config/database.php";

// Check that the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check that a meal ID was provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$meal_id = (int) $_GET['id'];

// Get the meal type before deleting
$sql = "
    SELECT meal_type
    FROM meal_entry
    WHERE meal_entry_id = ?
    AND user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $meal_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$meal = $result->fetch_assoc();

// Make sure the meal exists
if (!$meal) {
    header("Location: dashboard.php");
    exit();
}

$meal_type = $meal['meal_type'];

// Delete only the selected meal belonging to the logged-in user
$sql = "
    DELETE FROM meal_entry
    WHERE meal_entry_id = ?
    AND user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $meal_id, $user_id);
$stmt->execute();

// Set delete message
$_SESSION['delete_meal'] = $meal_type;
$_SESSION['delete_message'] = "Meal deleted successfully.";

// Return to dashboard
header("Location: dashboard.php");
exit();
