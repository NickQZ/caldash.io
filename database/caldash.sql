-- DATABASE SETUP SQL SCRIPT

-- Create database
CREATE DATABASE IF NOT EXISTS caldash
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Select database
USE caldash;


-- USER TABLE

CREATE TABLE IF NOT EXISTS users (
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

CREATE TABLE IF NOT EXISTS FOOD (
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

CREATE TABLE IF NOT EXISTS MEAL_ENTRY (
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
        REFERENCES users (user_id)
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
    ),

    (
        'Salmon',
        100.00,
        'g',
        208,
        0.00,
        13.00,
        20.00
    ),

    (
        'Lean Beef Mince',
        100.00,
        'g',
        176,
        0.00,
        10.00,
        20.00
    ),

    (
        'Chicken Thigh',
        100.00,
        'g',
        209,
        0.00,
        10.90,
        26.00
    ),

    (
        'Tuna',
        100.00,
        'g',
        116,
        0.00,
        0.80,
        25.50
    ),

    (
        'Turkey Breast',
        100.00,
        'g',
        135,
        0.00,
        1.50,
        29.00
    ),

    (
        'Pork Chop',
        100.00,
        'g',
        231,
        0.00,
        14.00,
        25.00
    ),

    (
        'Potato',
        100.00,
        'g',
        77,
        17.50,
        0.10,
        2.00
    ),

    (
        'Sweet Potato',
        100.00,
        'g',
        86,
        20.10,
        0.10,
        1.60
    ),

    (
        'Broccoli',
        100.00,
        'g',
        34,
        6.60,
        0.40,
        2.80
    ),

    (
        'Carrot',
        100.00,
        'g',
        41,
        9.60,
        0.20,
        0.90
    ),

    (
        'Avocado',
        100.00,
        'g',
        160,
        8.50,
        14.70,
        2.00
    ),

    (
        'Apple',
        100.00,
        'g',
        52,
        13.80,
        0.20,
        0.30
    ),

    (
        'Orange',
        100.00,
        'g',
        47,
        11.80,
        0.10,
        0.90
    ),

    (
        'Strawberries',
        100.00,
        'g',
        32,
        7.70,
        0.30,
        0.70
    ),

    (
        'Blueberries',
        100.00,
        'g',
        57,
        14.50,
        0.30,
        0.70
    ),

    (
        'Oats',
        100.00,
        'g',
        389,
        66.30,
        6.90,
        16.90
    ),

    (
        'Greek Yoghurt',
        100.00,
        'g',
        97,
        3.90,
        5.00,
        9.00
    ),

    (
        'Peanut Butter',
        20.00,
        'g',
        118,
        4.00,
        10.00,
        5.00
    ),

    (
        'Cheddar Cheese',
        30.00,
        'g',
        121,
        0.40,
        10.00,
        7.50
    ),

    (
        'Wholemeal Bread',
        1.00,
        'slice',
        82,
        13.80,
        1.10,
        4.00
    ),

    (
        'Pasta',
        100.00,
        'g',
        157,
        30.90,
        0.90,
        5.80
    ),

    (
        'Spaghetti',
        100.00,
        'g',
        157,
        30.90,
        0.90,
        5.80
    ),

    (
        'Black Beans',
        100.00,
        'g',
        132,
        23.70,
        0.50,
        8.90
    ),

    (
        'Chickpeas',
        100.00,
        'g',
        164,
        27.40,
        2.60,
        8.90
    ),

    (
        'Almonds',
        30.00,
        'g',
        174,
        6.50,
        15.00,
        6.30
    ),

    (
        'Cashews',
        30.00,
        'g',
        166,
        9.10,
        13.20,
        5.50
    ),

    (
        'Protein Bar',
        1.00,
        'bar',
        200,
        20.00,
        7.00,
        20.00
    ),

    (
        'Milk',
        250.00,
        'ml',
        122,
        12.00,
        4.00,
        8.00
    ),

    (
        'Almond Milk',
        250.00,
        'ml',
        38,
        2.50,
        2.80,
        1.00
    ),

    (
        'Orange Juice',
        250.00,
        'ml',
        112,
        25.80,
        0.50,
        1.70
    ),

    (
        'Cereal',
        40.00,
        'g',
        150,
        30.00,
        1.50,
        3.00
    ),

    (
        'Toast',
        1.00,
        'slice',
        80,
        14.00,
        1.00,
        3.00
    ),

    (
        'Bacon',
        50.00,
        'g',
        216,
        0.70,
        16.80,
        15.00
    ),

    (
        'Sausage',
        1.00,
        'piece',
        180,
        3.00,
        15.00,
        8.00
    ),
    
    (
        'Scrambled Eggs',
        100.00,
        'g',
        148,
        1.60,
        10.00,
        10.00
    );

-- END OF SCRIPT