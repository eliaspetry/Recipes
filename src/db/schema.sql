-- Schema transaction to set up the database

-- RECIPES

-- Create the categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL UNIQUE
);

-- Create the recipes table
CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    difficulty SET("Easy", "Medium", "Hard") NOT NULL,
    prep_time_minutes INT NOT NULL,
    image_url VARCHAR(191) NOT NULL,
    instructions TEXT NOT NULL
);

-- Create junction table for the categories associated to each recipe
CREATE TABLE IF NOT EXISTS recipes_categories (
    recipe_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY(recipe_id, category_id),
    FOREIGN KEY(recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

-- USERS

-- Create the users table
CREATE TABLE IF NOT EXISTS users_pec3 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(191) NOT NULL UNIQUE,
    name VARCHAR(191) NOT NULL,
    surname VARCHAR(191) NOT NULL,
    password VARCHAR(191) NOT NULL
);