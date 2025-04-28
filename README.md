# Student Forum Application

A PHP-based student forum application for course discussions.

## Database Setup

1. Make sure you have XAMPP (or equivalent) installed and running Apache and MySQL services.
2. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
3. Create a new database named `coursework`
4. Import the database structure by either:
   - Using the SQL dump file provided
   - OR creating the tables manually with these SQL commands:

```sql
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Application Setup

1. Place the project files in your XAMPP htdocs folder (e.g., `C:\xampp\htdocs\coursework\`)
2. Initialize the database with sample data:
   - Navigate to the project folder in your browser: `http://localhost/coursework/app/config/init_db.php`
   - This will create sample users, modules, and posts

## Application Usage

1. Access the application at: `http://localhost/coursework/`
2. Login with one of these sample accounts:
    User::create('admin', 'admin@example.com', 'admin123');
    User::create('student1', 'student1@example.com', 'password123');
    User::create('student2', 'student2@example.com', 'password123');

## Features

- User registration and authentication
- Creating and viewing posts
- Module-specific discussions
- Image upload for posts
- Search functionality

## Database Structure

- **users**: Stores user account information
- **modules**: Contains different course modules
- **posts**: Stores all forum posts, linked to users and modules 