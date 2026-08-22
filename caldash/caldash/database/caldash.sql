--DATABASE SETUP SQL SCRIPT

-- Create database
CREATE DATABASE IF NOT EXISTS caldash
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Select database
USE caldash;


-- USER TABLE

CREATE TABLE `USER` (
    user_id INT AUTO_INCREMENT PRIMARY KEY,

    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,

    email VARCHAR(191) NOT NULL UNIQUE, -- 191 is the maximum length for indexed VARCHAR in MySQL with utf8mb4

    password_hash VARCHAR(255) NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);


-- FOOD TABLE

CREATE TABLE FOOD (
    food_id INT AUTO_INCREMENT PRIMARY KEY,

    food_name VARCHAR(100) NOT NULL,

    serving_size DECIMAL(5,2) NOT NULL,
    serving_unit VARCHAR(20) NOT NULL,

    calories INT NOT NULL,

    carbohydrates DECIMAL(6,2) NOT NULL,
    fat DECIMAL(6,2) NOT NULL,
    protein DECIMAL(6,2) NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

-- MEAL_ENTRY TABLE

CREATE TABLE MEAL_ENTRY (
    meal_entry_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    food_id INT NOT NULL,

    meal_type ENUM(
        'Breakfast',
        'Lunch',
        'Dinner',
        'Snack'
    ) NOT NULL,

    quantity_consumed DECIMAL(5,2) NOT NULL,

    meal_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign key to USER
    CONSTRAINT fk_meal_entry_user
        FOREIGN KEY (user_id)
        REFERENCES `USER` (user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Foreign key to FOOD
    CONSTRAINT fk_meal_entry_food
        FOREIGN KEY (food_id)
        REFERENCES FOOD (food_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- INDEXES

CREATE INDEX idx_meal_entry_user
    ON MEAL_ENTRY(user_id);

CREATE INDEX idx_meal_entry_food
    ON MEAL_ENTRY(food_id);

CREATE INDEX idx_meal_entry_date
    ON MEAL_ENTRY(meal_date);

CREATE INDEX idx_food_name
    ON FOOD(food_name);

-- INSERT SAMPLE DATA

INSERT INTO FOOD
    (
        food_name,
        serving_size,
        serving_unit,
        calories,
        carbohydrates,
        fat,
        protein
    )
VALUES
    (
        'Chicken Breast',
        100.00,
        'g',
        165,
        0.00,
        3.60,
        31.00
    ),

    (
        'White Rice',
        100.00,
        'g',
        130,
        28.20,
        0.30,
        2.70
    ),

    (
        'Banana',
        100.00,
        'g',
        89,
        22.80,
        0.30,
        1.10
    ),

    (
        'Whole Egg',
        50.00,
        'g',
        78,
        0.60,
        5.30,
        6.30
    ),

    (
        'White Bread',
        1.00,
        'slice',
        79,
        14.00,
        1.00,
        2.70
    );

-- END OF SCRIPT