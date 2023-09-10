-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 19, 2023 at 02:40 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scriblo`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `content` text NOT NULL,
  `replyId` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `follower_id`, `following_id`, `createdAt`) VALUES
(1, 1, 9, '2023-08-08 17:08:02'),
(2, 19, 1, '2023-08-08 19:02:29'),
(3, 1, 19, '2023-08-08 19:23:32');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `message` text NOT NULL,
  `isRead` varchar(10) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `slug` text NOT NULL,
  `authorId` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `tags` text NOT NULL,
  `readTime` varchar(10) NOT NULL,
  `publishDate` datetime DEFAULT NULL,
  `isHidden` varchar(10) NOT NULL,
  `pinned` varchar(50) NOT NULL DEFAULT 'false',
  `createdAt` datetime NOT NULL,
  `mediaFiles` text DEFAULT NULL,
  `coverImage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `postId` int(11) DEFAULT NULL,
  `tagId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `previews`
--

CREATE TABLE `previews` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `code` varchar(500) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `commentId` int(11) NOT NULL,
  `reason` text NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(12, 'Art'),
(43, 'Artificial Intelligence'),
(22, 'Beauty'),
(13, 'Books'),
(17, 'Business'),
(38, 'Career'),
(25, 'Comedy'),
(35, 'Cooking'),
(28, 'Crafts'),
(8, 'Decor'),
(33, 'Design'),
(46, 'DIY Projects'),
(23, 'Education'),
(14, 'Entertainment'),
(47, 'Entrepreneurship'),
(16, 'Environment'),
(6, 'Fashion'),
(9, 'Finance'),
(39, 'Fitness'),
(5, 'Food'),
(21, 'Gaming'),
(29, 'Gardening'),
(3, 'Health'),
(27, 'History'),
(42, 'Hobbies'),
(40, 'Inspiration'),
(20, 'Lifestyle'),
(24, 'Motivation'),
(34, 'Movies'),
(19, 'Music'),
(36, 'Nature'),
(10, 'Parenting'),
(45, 'Parenting Tips'),
(30, 'Pets'),
(18, 'Photography'),
(48, 'Photography Tips'),
(26, 'Politics'),
(31, 'Relationships'),
(2, 'Science'),
(11, 'Self-Improve'),
(41, 'Spirituality'),
(15, 'Sports'),
(1, 'Tech'),
(32, 'Technology'),
(4, 'Travel'),
(44, 'Travel Tips'),
(37, 'Writing');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(300) DEFAULT NULL,
  `verified` int(11) NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL,
  `avatar` text NOT NULL,
  `allowEmails` varchar(50) NOT NULL DEFAULT 'true',
  `url` text DEFAULT NULL,
  `coverImage` text DEFAULT NULL,
  `interests` text NOT NULL,
  `bio` text NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `verified`, `email`, `avatar`, `allowEmails`, `url`, `coverImage`, `interests`, `bio`, `createdAt`) VALUES
(1, 'Okoye Udoka', 'okoyeudoka1', 1, 'leviokoye@gmail.com', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/leviokoye@gmail.com_avatar.jpg', 'true', 'https://udokaokoye.com/', NULL, 'Tech,Business,Parenting Tips', 'Software Developer 👨🏾‍💻\r\nInspire💡 | Motivate ❤️\r\nI Share My Two Cents On Tech!\r\nPhotographer📸 / Owner @levishotit_\r\n', '2023-05-31 09:05:02'),
(9, 'Levi Okoye', 'leviokoye9', 0, 'okoyeul@mail.uc.edu', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/okoyeul@mail.uc.edu_avatar.jpg', 'true', NULL, NULL, 'Tech,Science,Photography', 'Software Developer 👨🏾‍💻\nInspire💡 | Motivate ❤️\nI Share My Two Cents On Tech!\nPhotographer📸 / Owner @levishotit_\nUC 🐾 27', '2023-06-02 14:40:06'),
(19, 'test', 'test_19', 0, 'leviokoye01@gmail.com', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/av1.jpg', 'true', NULL, NULL, 'Artificial Intelligence,Beauty,Business', 'test', '2023-08-08 19:02:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userID` (`userID`,`postId`),
  ADD KEY `fk_bookmarks_postId` (`postId`) USING BTREE;

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_userID` (`userID`),
  ADD KEY `replyId` (`replyId`),
  ADD KEY `fk_comments_postId` (`postId`) USING BTREE;

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `follower_id` (`follower_id`,`following_id`),
  ADD KEY `fk_followers_following_id` (`following_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `postId` (`postId`,`userId`),
  ADD KEY `fk_likes_userID` (`userId`) USING BTREE;

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_userID` (`userID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_authorId` (`authorId`),
  ADD KEY `slug` (`slug`(768));

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD UNIQUE KEY `uniq_postId_tagId` (`postId`,`tagId`),
  ADD KEY `post_tags_ibfk_2` (`tagId`);

--
-- Indexes for table `previews`
--
ALTER TABLE `previews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_postId` (`postId`),
  ADD KEY `fk_authorId` (`authorId`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reports_userID` (`userID`),
  ADD KEY `fk_reports_postId` (`postId`),
  ADD KEY `fk_reports_commentId` (`commentId`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `previews`
--
ALTER TABLE `previews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `fk_bookmark_postId` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bookmarks_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_postID` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `srk_replyID` FOREIGN KEY (`replyId`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `fk_followers_follower_id` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_followers_following_id` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_postID` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_likes_userID` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_authorId` FOREIGN KEY (`authorId`) REFERENCES `users` (`id`);

--
-- Constraints for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `fk_posts_tags_postId` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `previews`
--
ALTER TABLE `previews`
  ADD CONSTRAINT `fk_authorId` FOREIGN KEY (`authorId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_postId` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_commentId` FOREIGN KEY (`commentId`) REFERENCES `comments` (`id`),
  ADD CONSTRAINT `fk_reports_postId` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `fk_reports_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
