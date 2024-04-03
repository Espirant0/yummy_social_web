CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `description` text,
  `time` int,
  `author_id` int,
  `calories` float,
  `proteins` float,
  `carbs` float,
  `fats` float
);

CREATE TABLE IF NOT EXISTS`products` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `calories` float,
  `proteins` float,
  `carbs` float,
  `fats` float,
  `category_id` int,
  `measure_id` int
);

CREATE TABLE IF NOT EXISTS`recipe_product` (
  `recipe_id` int,
  `product_id` int,
  `value` float
);

CREATE TABLE IF NOT EXISTS `measures` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255)
);

CREATE TABLE IF NOT EXISTS `user_product` (
  `user_id` int,
  `product_id` int,
  `value` float
);

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255)
);

CREATE TABLE IF NOT EXISTS `images` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `path` varchar(255),
  `recipe_id` int,
  `is_cover` tinyint(1)
);

CREATE TABLE IF NOT EXISTS `planner` (
  `recipe_id` int,
  `user_id` int,
  `course_id` int,
  `date` date
);

CREATE TABLE IF NOT EXISTS `course` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255)
);

CREATE TABLE IF NOT EXISTS `featured` (
  `user_id` int,
  `recipe_id` int
);
