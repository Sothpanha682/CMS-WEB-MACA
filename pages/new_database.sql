-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2025 at 09:48 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movie_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `setting_id` int NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `name`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(3, 'Animation'),
(4, 'Biography'),
(5, 'Comedy'),
(6, 'Crime'),
(7, 'Documentary'),
(8, 'Drama'),
(9, 'Family'),
(10, 'Fantasy'),
(11, 'History'),
(12, 'Horror'),
(13, 'Music'),
(14, 'Mystery'),
(15, 'Romance'),
(16, 'Science Fiction'),
(17, 'Thriller'),
(18, 'War'),
(19, 'Western');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `poster_url` varchar(255) NOT NULL,
  `trailer_url` varchar(255) NOT NULL,
  `release_year` year NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `poster_url`, `trailer_url`, `release_year`, `rating`, `featured`, `created_at`, `updated_at`) VALUES
(1, 'The Matrix', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 'https://m.media-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_.jpg', 'https://www.youtube.com/watch?v=vKQi3bBA1y8', '1999', 8.7, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(2, 'Inception', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.', 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_.jpg', 'https://www.youtube.com/watch?v=YoHD9XEInc0', '2010', 8.8, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(3, 'The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', 'https://m.media-amazon.com/images/M/MV5BMDFkYTc0MGEtZmNhMC00ZDIzLWFmNTEtODM1ZmRlYWMwMWFmXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_.jpg', 'https://www.youtube.com/watch?v=6hB3S9bIaco', '1994', 9.3, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(4, 'Pulp Fiction', 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'https://m.media-amazon.com/images/M/MV5BNGNhMDIzZTUtNTBlZi00MTRlLWFjM2ItYzViMjE3YzI5MjljXkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_.jpg', 'https://www.youtube.com/watch?v=s7EdQ4FqbhY', '1994', 8.9, 0, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(5, 'The Dark Knight', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_.jpg', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', '2008', 9.0, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(6, 'Parasite', 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.', 'https://m.media-amazon.com/images/M/MV5BYWZjMjk3ZTItODQ2ZC00NTY5LWE0ZDYtZTI3MjcwN2Q5NTVkXkEyXkFqcGdeQXVyODk4OTc3MTY@._V1_.jpg', 'https://www.youtube.com/watch?v=5xH0HfJHsaY', '2019', 8.6, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(7, 'Avengers: Endgame', 'After the devastating events of Avengers: Infinity War, the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos\' actions and restore balance to the universe.', 'https://m.media-amazon.com/images/M/MV5BMTc5MDE2ODcwNV5BMl5BanBnXkFtZTgwMzI2NzQ2NzM@._V1_.jpg', 'https://www.youtube.com/watch?v=TcMBFSGVi1c', '2019', 8.4, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(8, 'Joker', 'In Gotham City, mentally troubled comedian Arthur Fleck is disregarded and mistreated by society. He then embarks on a downward spiral of revolution and bloody crime. This path brings him face-to-face with his alter-ego: the Joker.', 'https://m.media-amazon.com/images/M/MV5BNGVjNWI4ZGUtNzE0MS00YTJmLWE0ZDctN2ZiYTk2YmI3NTYyXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_.jpg', 'https://www.youtube.com/watch?v=zAGVQLHvwOY', '2019', 8.4, 0, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(9, 'Interstellar', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.', 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDktN2IxOS00OGEyLWFmMjktY2FiMmZkNWIyODZiXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_.jpg', 'https://www.youtube.com/watch?v=zSWdZVtXT7E', '2014', 8.6, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08'),
(10, 'The Godfather', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 'https://m.media-amazon.com/images/M/MV5BM2MyNjYxNmUtYTAwNi00MTYxLWJmNWYtYzZlODY3ZTk3OTFlXkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_.jpg', 'https://www.youtube.com/watch?v=sY1S34973zA', '1972', 9.2, 1, '2025-06-23 08:50:08', '2025-06-23 08:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `movie_genres`
--

CREATE TABLE `movie_genres` (
  `movie_id` int NOT NULL,
  `genre_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movie_genres`
--

INSERT INTO `movie_genres` (`movie_id`, `genre_id`) VALUES
(1, 1),
(2, 1),
(5, 1),
(7, 1),
(7, 2),
(9, 2),
(6, 5),
(4, 6),
(5, 6),
(8, 6),
(10, 6),
(3, 8),
(4, 8),
(5, 8),
(6, 8),
(8, 8),
(9, 8),
(10, 8),
(1, 16),
(2, 16),
(7, 16),
(9, 16),
(2, 17),
(6, 17),
(8, 17);

-- --------------------------------------------------------

--
-- Table structure for table `movie_servers`
--

CREATE TABLE `movie_servers` (
  `server_id` int NOT NULL,
  `movie_id` int NOT NULL,
  `server_name` varchar(100) NOT NULL,
  `server_url` varchar(255) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `movie_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `movie_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 9.0, 'Revolutionary film that defined a generation of sci-fi movies. The special effects still hold up today!', '2025-06-23 08:50:08'),
(2, 2, 1, 8.5, 'Christopher Nolan at his best. The concept is mind-bending and the execution is flawless.', '2025-06-23 08:50:08'),
(3, 3, 1, 9.5, 'One of the greatest films ever made. The story of hope and redemption is timeless.', '2025-06-23 08:50:08'),
(4, 5, 1, 9.0, 'Heath Ledger\'s performance as the Joker is legendary. A perfect superhero film.', '2025-06-23 08:50:08'),
(5, 7, 1, 8.0, 'A satisfying conclusion to the Infinity Saga. Emotional and action-packed.', '2025-06-23 08:50:08'),
(6, 9, 1, 9.0, 'A visual masterpiece with a powerful emotional core. The science is fascinating.', '2025-06-23 08:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$eT5epRYDZHbrVlA/qyDEzOUbgNf.CENte3YFzD0QnXZSv6FGuQB5.', 'admin', '2025-06-23 08:50:08', '2025-06-23 02:07:02'),
(2, 'khmercyber', 'khmercyber@gmail.com', '$2y$10$PgUC.y5nY2Qlet253M4SteqaDZzT8aEkEbryOelMqXj41YRU1tAny', 'admin', '2025-06-23 09:08:03', '2025-06-23 02:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `watchlist_id` int NOT NULL,
  `user_id` int NOT NULL,
  `movie_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`watchlist_id`, `user_id`, `movie_id`, `created_at`) VALUES
(3, 1, 3, '2025-06-23 09:04:55'),
(4, 1, 5, '2025-06-23 09:05:00'),
(5, 2, 5, '2025-06-23 09:43:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD PRIMARY KEY (`movie_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `movie_servers`
--
ALTER TABLE `movie_servers`
  ADD PRIMARY KEY (`server_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`watchlist_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movie_servers`
--
ALTER TABLE `movie_servers`
  MODIFY `server_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `watchlist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD CONSTRAINT `movie_genres_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE;

--
-- Constraints for table `movie_servers`
--
ALTER TABLE `movie_servers`
  ADD CONSTRAINT `movie_servers_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
