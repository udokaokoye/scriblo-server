-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 29, 2023 at 09:11 PM
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
(58, 1, 9, '2023-07-21 10:26:56'),
(60, 10, 1, '2023-07-29 13:22:33');

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

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `slug`, `authorId`, `title`, `summary`, `content`, `tags`, `readTime`, `publishDate`, `isHidden`, `pinned`, `createdAt`, `mediaFiles`, `coverImage`) VALUES
(110, 'okoyeudoka1-110', 1, 'Things I Wish I Knew Before Programming', 'No matter what level you are in your coding journey, there is always a point where you’ve made mistakes and looking back at those mistakes now you wish those were things you knew before going into programming. Here are the things I wish I knew before I started programming.', '<p>No matter what level you are in your coding journey, there is always a point where you’ve made mistakes and looking back at those mistakes now you wish those were things you knew before going into programming. Here are the things I wish I knew before I started programming.</p>\r\n<p></p>\r\n<iframe width=\"auto\" height=\"auto\" src=\"https://www.youtube.com/embed/JuEiLw0g13w\" frameBorder=\"0\"></iframe>\r\n<p></p>\r\n<h2>Don’t Compare yourself with others.</h2>\r\n<p>comparing myself with other people on social media who clearly had more experience than I did nearly killed my dream. most people here know my story, for sometime in 2020 I stoped coding, I felt this carrear is not for me because I couldn’t build what that guy I saw on Instagram who has 5 years experience was building. One thing we need to realize is that imposter syndrome is real! the more you compare yourself with others the more it’s gonna get to you. if you feel you don\'t know something the truth is that you don’t know it, it won\'t do you any good comparing yourself with people that know it or don\'t know it, JUST LEARN IT! Comparison at a very tender stage is very bad and try as much as possible to stay away from it. Remember you are very skilled for the level your’e in. all that really matters is that you keep improving</p>\r\n<p></p>\r\n<img src=\"https://scriblo.s3.us-east-2.amazonaws.com/images/okoyeudoka1-draft-things-i-wish-i-knew-before-programming_1_7028377f-8fce-481f-912e-3e6081a37154.jpg\" alt=\"undefined\" style=\"height: 100%;width: 100%\"/>\r\n<p></p>\r\n<h2>It’s ok to cheat.</h2>\r\n<p>I remember when I was building a project with javascript and got stuck on a problem, this was not a logical problem, it was a syntax problem, I couldn’t remember the syntax to append something to an array in javascript, I spent hours trying out other crazy methods to append a value to an array instead of just googling it!. I felt good programmers don’t google, and I felt that was the way to become a good programmer. but today I tell thee, THAT’S A BIG LIE. See, I\'ve come to realize that not googling things as a developer will not just make the learning phase difficult but it will also make you quit early because you’ll feel you’re dumb and you can’t learn anything. Googling is totally fine, see the best developer out there still googles. Now if I don\'t know anything, I don\'t even stress it, I just google it or I call on my never failing friend chat-got.</p>\r\n<p></p>\r\n<h2>Find A Mentor.</h2>\r\n<p>A mentor can have a profound impact on your growth and development as a software developer. Their experience and expertise in the industry can provide you with valuable knowledge and insights that go beyond what you can learn from any YouTube video or online resources. I\'ve always been a solo developer, no mentor, no developer friends, no nothing. I was riding solo. and that explains why I made a lot of mistakes. A mentor can offer insights into best practices, emerging technologies, and real-world scenarios. they can also provide career guidance, help with code review and problem-solving, offer networking opportunities (that’s a big one), and provide motivation and support.</p>\r\n<p>Overall, a mentor can accelerate your learning, enhance your skills, and contribute to your professional growth as a software developer.</p>\r\n<p></p>\r\n<p></p>\r\n<h2>Just Build It.</h2>\r\n<p>There were times I was stuck in tutorial hell, binge watching tutorials for hours but never implement any of that knowledge building projects. my excuse was always “I don’t think I know this language well to build this project, let me watch another tutorial“. that there was the problem, I always felt wasn’t knowledgeable enough to build projects. the truth is that you can’t measure you knowledge about a programming language without building with it. SO JUST BUILD IT. you can’t be the best programmer by binge watching all those tutorials, you just have to man up and build those projects, if you fail, figure out what you did wrong or find what aspect you’re lacking on, improve on it then try the project again. it’s that simple!</p>\r\n<p></p>\r\n<p>[wrap]</p>\r\n<p>like they say experience is your best teacher</p>\r\n', 'Tech', '4', '2023-07-26 00:10:53', '0', 'false', '2023-07-26 00:10:53', 'a:1:{i:0;s:0:\"\";}', 'https://scriblo.s3.us-east-2.amazonaws.com/images/okoyeudoka1-draft-things-i-wish-i-knew-before-programming_cover_5b9d6ad0-a800-4f21-95e1-9d3d367a875d.jpg'),
(147, 'okoyeudoka1-draft-what-s-scalability-in-software-developmen', 1, 'What\'s Scalability in software developmen', 'Discuss the concept of scalability in web and mobile app development, emphasizing the ability to handle increased load and user demand without compromising performance.', '<p>Discuss the concept of scalability in web and mobile app development, emphasizing the ability to handle increased load and user demand without compromising performance.</p>\r\n<p>Scalability in software development refers to the ability of a system to handle increased workload or user demands without sacrificing performance or reliability. To explain it to a beginner, you can use the following analogy:</p>\r\n<p>Think of a new mall that sells clothes. Initially, they have a few customers and can handle their orders easily with one register. However, as people get to know they running a 80% discount, more and more customers start coming in. The store will needs to scale to meet the increased demand.</p>\r\n<p>In software development, it\'s similar. Imagine you\'re building a web application. Initially, you may have a small number of users, and your server can handle their requests without any problems. However, as your application becomes popular and more users start using it, you need to ensure that your system can handle the increased traffic efficiently.</p>\r\n<p>Scalability involves designing your software in a way that allows it to grow and handle a larger number of users or data without becoming slow or unstable. This could include techniques like optimizing code, using efficient algorithms, and designing a robust architecture that can handle increased loads.</p>\r\n<p>Just like the bakery may hire more bakers, expand their kitchen space, or streamline their processes to handle more cake orders, software developers need to plan for scalability from the beginning, anticipating future growth and implementing strategies to ensure their software can handle it.</p>\r\n', 'Technology,Tech', '2', '2023-07-29 13:11:15', '0', 'false', '2023-07-29 13:11:15', 'a:1:{i:0;s:0:\"\";}', 'https://scriblo.s3.us-east-2.amazonaws.com/images/okoyeudoka1-draft-what-s-scalability-in-software-development_cover_1195c37d-2cce-4a77-9532-a1c8abe4ef60.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `postId` int(11) DEFAULT NULL,
  `tagId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_tags`
--

INSERT INTO `post_tags` (`postId`, `tagId`) VALUES
(110, 1),
(147, 1),
(147, 32);

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

--
-- Dumping data for table `previews`
--

INSERT INTO `previews` (`id`, `postId`, `authorId`, `slug`, `code`, `createdAt`) VALUES
(26, 147, 1, '3bc8b201-77e7-4c07-915f-86e824c9999f', 'Yx2u2FYGmbup+T9VFfskVEy8VhUSF0Ewx5rA2Havpac=', '2023-07-29 13:11:15');

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

INSERT INTO `users` (`id`, `name`, `username`, `email`, `avatar`, `allowEmails`, `url`, `coverImage`, `interests`, `bio`, `createdAt`) VALUES
(1, 'Okoye Udoka', 'okoyeudoka1', 'leviokoye@gmail.com', 'https://lh3.googleusercontent.com/a/AAcHTtcTRFHY0kzM_9IlsKk1m2M2AhztvfXEnqtc5QO3wA=s96-c', 'true', NULL, NULL, 'Tech,Business,Parenting Tips', 'Software Developer 👨🏾‍💻\nInspire💡 | Motivate ❤️\nI Share My Two Cents On Tech!\nPhotographer📸 / Owner @levishotit_\nUC 🐾 27', '2023-05-31 09:05:02'),
(9, 'Levi Okoye', 'leviokoye9', 'okoyeul@mail.uc.edu', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/okoyeul@mail.uc.edu_avatar.jpg', 'true', NULL, NULL, 'Tech,Science,Photography', 'Software Developer 👨🏾‍💻\nInspire💡 | Motivate ❤️\nI Share My Two Cents On Tech!\nPhotographer📸 / Owner @levishotit_\nUC 🐾 27', '2023-06-02 14:40:06'),
(10, 'Udochukwuka Okoye', 'udokaokoye10', 'leviokoye01@gmail.com', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/leviokoye01@gmail.com_avatar.jpg', 'true', NULL, NULL, 'Motivation,Comedy,Politics', 'THis is my bio', '2023-06-04 12:34:01'),
(11, 'Peter Doe', 'peterdoe11', 'peter@mail.com', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/peter@mail.com_avatar.jpg', 'true', NULL, NULL, 'Tech,Entertainment,Gaming', 'This is Peter I\'m a software developer', '2023-06-05 20:30:58'),
(17, 'Levi Dev', 'levidev_17', 'sam@mail.com', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/sam@mail.com_avatar.jpg', 'true', NULL, NULL, 'Entertainment,Science,Writing', 'Top Rated Freelancer || Blogger || Cross-Platform App Developer || Web Developer || Open Source Contributor', '2023-06-11 17:40:02'),
(18, 'Udoka Levi Okoye', 'udokaleviokoye_18', 'udokaokoye.us@gmail.com', 'https://scriblo.s3.us-east-2.amazonaws.com/avatars/av3.jpg', 'true', NULL, NULL, 'Technology,Tech,Self-Improve', 'This is my bio, nothing much to write right now.', '2023-07-21 10:46:49');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `previews`
--
ALTER TABLE `previews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
