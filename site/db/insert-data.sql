--
-- table `comment`
--

INSERT INTO `comment` (`id`, `post`, `author`, `content`, `creation_date`, `edited`) VALUES
(1, 2, 2, 'comment 1', '2022-12-25 00:15:12', 0),
(2, 1, 2, 'xdddddddd', '2022-12-25 00:07:12', 0);

-- --------------------------------------------------------

--
-- table `community`
--

INSERT INTO `community` (`id`, `author`, `name`, `description`, `creation_date`) VALUES
(1, 1, 'comm1', 'description comm1', '2022-12-21 22:04:59'),
(2, 2, 'comm2', 'description comm2', '2022-12-24 22:04:59');

-- --------------------------------------------------------

--
-- table `following`
--

INSERT INTO `following` (`follower`, `followed`) VALUES
(1, 2),
(2, 1),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- table `notification`
--

INSERT INTO `notification` (`id`, `user`, `seen`, `date`, `content`) VALUES
(1, 1, 1, '2022-12-21 18:10:40', 'def followed you'),
(2, 1, 0, '2022-12-24 18:10:40', 'ghi followed you');

-- --------------------------------------------------------

--
-- table `participation`
--

INSERT INTO `participation` (`user`, `community`, `date_joined`, `date_left`, `reason_left`) VALUES
(1, 1, '2022-12-21 22:06:13', NULL, NULL),
(2, 1, '2022-12-24 22:17:00', NULL, NULL),
(2, 2, '2022-12-24 22:06:13', NULL, NULL),
(3, 1, '2022-12-21 22:07:27', '2022-12-23 22:07:27', 'ban'),
(3, 2, '2022-12-24 22:07:27', NULL, NULL);

-- --------------------------------------------------------

--
-- table `post`
--

INSERT INTO `post` (`id`, `author`, `community`, `title`, `content`, `attachment`, `creation_date`, `edited`) VALUES
(1, 1, 1, 'xd', 'first post', NULL, '2022-12-23 00:13:57', 0),
(2, 3, 2, 'lmao', 'post in comm2', NULL, '2022-12-24 00:14:15', 0);

-- --------------------------------------------------------

--
-- table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `username`, `bio`, `creation_date`) VALUES
(1, 'abc@gmail.com', 'pass', 'abc', 'abc bio', '2022-12-22 17:59:15'),
(2, 'def@gmail.com', 'pass', 'def', 'def bio', '2022-12-23 17:59:15'),
(3, 'ghi@gmail.com', 'pass', 'ghi', NULL, '2022-12-24 18:00:18');

-- --------------------------------------------------------

--
-- table `vote_comment`
--

INSERT INTO `vote_comment` (`user`, `comment`, `vote`) VALUES
(1, 1, -1),
(3, 1, 1);

-- --------------------------------------------------------

--
-- table `vote_post`
--

INSERT INTO `vote_post` (`user`, `post`, `vote`) VALUES
(2, 1, 1),
(2, 2, -1);
