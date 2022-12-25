<?php

class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    ///////////////////////////
    // Post related queries  //
    ///////////////////////////

    public function addPost($authorId, $communityId, $title, $content) {
        $sql = "INSERT INTO post (author, community, title, content, creation_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiss", $authorId, $communityId, $title, $content);
        return $stmt->execute();
    }

    public function updatePost($id, $title, $content) {
        $sql = "UPDATE post SET title = ?, content = ?, edited = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $id);
        return $stmt->execute();
    }

    public function deletePost($id) {
        $sql = "DELETE FROM post WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getPost($id) {
        $sql = "SELECT * FROM post WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getRandomPosts($limit = 10) {
        $sql = "SELECT * FROM post ORDER BY RAND() LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostsByCommunity($communityId, $limit = 10) {
        $sql = "SELECT * FROM post WHERE community = ? ORDER BY creation_date DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $communityId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostsByCommunities(Array $communities, int $limit = 10) {
        $in  = str_repeat('?,', count($communities)-1) . '?';
        $sql = "SELECT * FROM post WHERE community IN ($in) ORDER BY creation_date DESC LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($communities)), ...$communities);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostsByUser($userId, $limit = 10) {
        $sql = "SELECT * FROM post WHERE author = ? ORDER BY creation_date DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostsByUsers(Array $users, int $limit = 10) {
        $in  = str_repeat('?,', count($users)-1) . '?';
        $sql = "SELECT * FROM post WHERE author IN ($in) ORDER BY creation_date DESC LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($users)), ...$users);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function votePost($userId, $postId, $vote = 0) {
        // check if same vote is already in database, if so, set vote to 0
        $sql = "SELECT vote FROM vote_post WHERE user = ? AND post = ? AND vote = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $userId, $postId, $vote);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $vote = 0;
        }
        // insert or update vote
        $sql = "INSERT INTO vote_post (user, post, vote) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $postId, $vote, $vote);
        return $stmt->execute();
    }

    public function getPostLikes($postId) {
        $sql = "SELECT COUNT(*) FROM vote_post WHERE post = ? AND vote = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["count"];
    }

    public function getPostDislikes($postId) {
        $sql = "SELECT COUNT(*) FROM vote_post WHERE post = ? AND vote = -1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["count"];
    }

    public function getPostNumberOfComments($postId) {
        $sql = "SELECT COUNT(*) FROM comment WHERE post = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["count"];
    }

    ///////////////////////////
    // User related queries  //
    ///////////////////////////

    public function addUser($email, $password, $username) {
        $sql = "INSERT INTO user (email, password, username, creation_date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $email, $password, $username);
        return $stmt->execute();
    }

    public function updateUser($userId, $email, $password, $username, $bio = null) {
        $sql = "UPDATE user SET email = ?, password = ?, username = ?, bio = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $email, $password, $username, $bio, $userId);
        return $stmt->execute();
    }

    public function deleteUser($userId) {
        $sql = "DELETE FROM user WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function logUser($email, $password) {
        $sql = "SELECT COUNT(*) FROM user WHERE email = ? AND password = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["count"];
    }

    public function getUser($id_or_email_or_username) {
        if (is_numeric($id_or_email_or_username)) {
            $sql = "SELECT id, email, username, bio, creation_date FROM user WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id_or_email_or_username);
        } else if (filter_var($id_or_email_or_username, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT id, email, username, bio, creation_date FROM user WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $id_or_email_or_username);
        } else {
            $sql = "SELECT id, email, username, bio, creation_date FROM user WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $id_or_email_or_username);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUsers(Array $userIds, int $limit) {
        $in = str_repeat('?,', count($userIds) - 1) . '?';
        $sql = "SELECT id, email, username, bio, creation_date FROM user WHERE id IN ($in) LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($userIds)), ...$userIds);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function followUser($followerId, $followedId) {
        $sql = "INSERT INTO following (follower, followed) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $followerId, $followedId);
        return $stmt->execute();
    }

    public function unfollowUser($followerId, $followedId) {
        $sql = "DELETE FROM following WHERE follower = ? AND followed = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $followerId, $followedId);
        return $stmt->execute();
    }

    public function getFollowers($userId) {
        $sql = "SELECT id, email, username, bio, creation_date
                FROM user
                WHERE user.id IN (SELECT follower FROM following WHERE followed = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFollowed($userId) {
        $sql = "SELECT id, email, username, bio, creation_date
                FROM user
                WHERE user.id IN (SELECT followed FROM following WHERE follower = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFollowersCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM following WHERE followed = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    public function getFollowedCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM following WHERE follower = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    public function isFollowing($followerId, $followedId) {
        $sql = "SELECT * FROM following WHERE follower = ? AND followed = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $followerId, $followedId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    ////////////////////////////////////
    // Notifications related queries  //
    ////////////////////////////////////

    public function addNotification($userId, $content) {
        $sql = "INSERT INTO notification (user, date, content) VALUES (?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $userId, $content);
        return $stmt->execute();
    }

    public function deleteNotification($notificationId) {
        $sql = "DELETE FROM notification WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $notificationId);
        return $stmt->execute();
    }

    public function getNotifications($userId, $limit = 10) {
        $sql = "SELECT * FROM notification WHERE user = ? ORDER BY date DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readNotification($notificationId) {
        $sql = "UPDATE notification SET seen = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $notificationId);
        return $stmt->execute();
    }

    public function getUnreadNotificationsCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notification WHERE user = ? AND seen = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    ////////////////////////////////
    // Community related queries  //
    ////////////////////////////////

    public function addCommunity($authorId, $name, $description) {
        $sql = "INSERT INTO community (author, name, description, creation_date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $authorId, $name, $description);
        return $stmt->execute();
    }

    public function updateCommunity($communityId, $description) {
        $sql = "UPDATE community SET description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $description, $communityId);
        return $stmt->execute();
    }

    public function deleteCommunity($communityId) {
        $sql = "DELETE FROM community WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $communityId);
        return $stmt->execute();
    }

    public function getCommunity($communityId_or_communityName) {
        if (is_numeric($communityId_or_communityName)) {
            $sql = "SELECT * FROM community WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $communityId_or_communityName);
        } else {
            $sql = "SELECT * FROM community WHERE name = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $communityId_or_communityName);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getCommunities(Array $communityIds) {
        $in = str_repeat('?,', count($communityIds) - 1) . '?';
        $sql = "SELECT * FROM community WHERE id IN ($in)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(str_repeat("i", count($communityIds)), ...$communityIds);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function joinCommunity($userId, $communityId) {
        // check if user is already participating in the community
        $isAlreadyParticipating = $this->isParticipating($userId, $communityId);
        if ($isAlreadyParticipating) {
            return false;
        }
        // if not, add him to the community
        $sql = "INSERT INTO participation (user, community, date_joined) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $communityId);
        return $stmt->execute();
    }

    public function leaveCommunity($userId, $communityId, $reason = "no reason given") {
        // check if user is participating in the community, if not return 0
        $isAlreadyParticipating = $this->isParticipating($userId, $communityId);
        if (!$isAlreadyParticipating) {
            return false;
        }
        // if he is, update his participation by setting the date_left and reason_left
        $sql = "UPDATE participation SET date_left = NOW(), reason_left = ? WHERE user = ? AND community = ? AND date_left IS NULL ORDER BY date_joined DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $reason, $userId, $communityId);
        return $stmt->execute();
    }

    public function isParticipating($userId, $communityId) {
        $sql = "SELECT * FROM participation WHERE user = ? AND community = ? AND date_left IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $communityId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getParticipatingUserCount($communityId) {
        $sql = "SELECT COUNT(*) as count FROM participation WHERE community = ? AND date_left IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $communityId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    //////////////////////////////
    // Comment related queries  //
    //////////////////////////////

    public function addComment($postId, $authorId, $content) {
        $sql = "INSERT INTO comment (author, post, content, creation_date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iis", $authorId, $postId, $content);
        return $stmt->execute();
    }

    public function deleteComment($commentId) {
        $sql = "DELETE FROM comment WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentId);
        return $stmt->execute();
    }

    public function updateComment($commentId, $content) {
        $sql = "UPDATE comment SET edited = 1, content = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $content, $commentId);
        return $stmt->execute();
    }

    public function getCommentsByPost($postId) {
        $sql = "SELECT * FROM comment WHERE post = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCommentsByUser($userId) {
        $sql = "SELECT * FROM comment WHERE author = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function voteComment($commentId, $userId, $vote = 0) {
        // check if same vote is already in database, if so, set vote to 0
        $sql = "SELECT * FROM vote_comment WHERE user = ? AND comment = ? AND vote = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $userId, $commentId, $vote);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()) {
            $vote = 0;
        }
        // insert or update vote
        $sql = "INSERT INTO vote_comment (user, comment, vote) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $commentId, $vote, $vote);
        return $stmt->execute();
    }

    public function getCommentLikes($commentId) {
        $sql = "SELECT COUNT(*) as count FROM vote_comment WHERE comment = ? AND vote = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    public function getCommentDislikes($commentId) {
        $sql = "SELECT COUNT(*) as count FROM vote_comment WHERE comment = ? AND vote = -1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }
}
