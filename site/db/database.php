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
        $stmt->execute();
    }

    public function updatePost($id, $title, $content) {
        $sql = "UPDATE post SET title = ?, content = ?, edited = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
    }

    public function deletePost($id) {
        $sql = "DELETE FROM post WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
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

    public function getPostsByUser($userId, $limit = 10) {
        $sql = "SELECT * FROM post WHERE author = ? ORDER BY creation_date DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function votePost($userId, $postId, $vote = 0) {
        $sql = "INSERT INTO vote_post (user, post, vote) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $postId, $vote, $vote);
        $stmt->execute();
    }

    public function getPostLikes($postId) {
        $sql = "SELECT COUNT(*) FROM vote_post WHERE post = ? AND vote = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["COUNT(*)"];
    }

    public function getPostDislikes($postId) {
        $sql = "SELECT COUNT(*) FROM vote_post WHERE post = ? AND vote = -1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["COUNT(*)"];
    }

    public function getPostNumberOfComments($postId) {
        $sql = "SELECT COUNT(*) FROM comment WHERE post = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["COUNT(*)"];
    }

    ///////////////////////////
    // User related queries  //
    ///////////////////////////

    public function addUser($email, $password, $username) {
        $sql = "INSERT INTO user (email, password, username, creation_date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $email, $password, $username);
        $stmt->execute();
    }

    public function updateUser($userId, $email, $password, $username, $bio) {
        $sql = "UPDATE user SET email = ?, password = ?, username = ?, bio = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $email, $password, $username, $bio, $userId);
        $stmt->execute();
    }

    public function deleteUser($userId) {
        $sql = "DELETE FROM user WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    public function logUser($email, $password) {
        $sql = "SELECT * FROM user WHERE email = ? AND password = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUser($id_or_email_or_username) {
        if (is_numeric($id_or_email_or_username)) {
            $sql = "SELECT * FROM user WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id_or_email_or_username);
        } else if (filter_var($id_or_email_or_username, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM user WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $id_or_email_or_username);
        } else {
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $id_or_email_or_username);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function followUser($followerId, $followedId) {
        $sql = "INSERT INTO following (follower, followed) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $followerId, $followedId);
        $stmt->execute();
    }

    public function unfollowUser($followerId, $followedId) {
        $sql = "DELETE FROM following WHERE follower = ? AND followed = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $followerId, $followedId);
        $stmt->execute();
    }

    public function getFollowers($userId) {
        $sql = "SELECT * FROM following WHERE followed = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFollowed($userId) {
        $sql = "SELECT * FROM following WHERE follower = ?";
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
        return $result->fetch_assoc()['COUNT(*)'];
    }

    public function getFollowedCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM following WHERE follower = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'];
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
        $stmt->execute();
    }

    public function deleteNotification($notificationId) {
        $sql = "DELETE FROM notification WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $notificationId);
        $stmt->execute();
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
        $stmt->execute();
    }

    public function getUnreadNotificationsCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notification WHERE user = ? AND seen = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'];
    }

    ////////////////////////////////
    // Community related queries  //
    ////////////////////////////////

    public function addCommunity($authorId, $name, $description) {
        $sql = "INSERT INTO community (author, name, description, creation_date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $authorId, $name, $description);
        $stmt->execute();
    }

    public function updateCommunity($id, $description) {
        $sql = "UPDATE community SET description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $description, $id);
        $stmt->execute();
    }

    public function deleteCommunity($id) {
        $sql = "DELETE FROM community WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function getCommunity($id_or_name) {
        if (is_numeric($id_or_name)) {
            $sql = "SELECT * FROM community WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id_or_name);
        } else {
            $sql = "SELECT * FROM community WHERE name = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $id_or_name);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function joinCommunity($userId, $communityId) {
        $sql = "INSERT INTO participation (user, community, date_joined) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $communityId);
        $stmt->execute();
    }

    public function leaveCommunity($userId, $communityId, $reason = "no reason given") {
        $sql = "UPDATE participation SET date_left = NOW(), reason_left = ? WHERE user = ? AND community = ? AND date_left IS NULL ORDER BY date_joined DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $reason, $userId, $communityId);
        $stmt->execute();
    }

    //////////////////////////////
    // Comment related queries  //
    //////////////////////////////

    public function addComment($authorId, $postId, $content) {
        $sql = "INSERT INTO comment (author, post, content, creation_date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iis", $authorId, $postId, $content);
        $stmt->execute();
    }

    public function deleteComment($id) {
        $sql = "DELETE FROM comment WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function updateComment($id, $content) {
        $sql = "UPDATE comment SET content = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $content, $id);
        $stmt->execute();
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

    public function voteComment($userId, $commentId, $vote = 0) {
        $sql = "INSERT INTO vote_comment (user, comment, vote) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $commentId, $vote, $vote);
        $stmt->execute();
    }

    public function getCommentLikes($commentId) {
        $sql = "SELECT COUNT(*) as count FROM vote_comment WHERE comment = ? AND vote = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'];
    }

    public function getCommentDislikes($commentId) {
        $sql = "SELECT COUNT(*) as count FROM vote_comment WHERE comment = ? AND vote = -1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'];
    }
}
