<?php
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die('Connection failed: ' . $this->db->connect_error);
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    // Get the maximum id from the post and comment tables, this is used to generate new ids
    private function getMaxId() {
        $sql = 'SELECT MAX(id)
                FROM (SELECT id FROM post UNION SELECT id FROM comment) as id';
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['MAX(id)'] ?? 0;
    }

    ///////////////////////////
    // User related queries  //
    ///////////////////////////

    /**
     * Add a new user to the database
     * @param string $email email of the user
     * @param string $password password of the user
     * @param string $username username of the user
     * @return bool true if the user was added, false otherwise
     */
    public function addUser(string $email, string $password, string $username): bool {
        $sql = 'INSERT IGNORE INTO user (email, password, username, creation_date) VALUES (?, ?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $params = [$email, password_hash($password,PASSWORD_DEFAULT), $username];
        $stmt->bind_param('sss', ...$params);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get a list of users given their ids or emails/usernames
     * @param int[]|string[] $users ids or emails or usernames of the users, if null all users will be returned
     * @param int $limit maximum number of users to return (return size could be less)
     * @param int $offset number of users to skip
     * @return array array of users
     */
    public function getUsers(Array $users = null, int $limit = 0, int $offset = 0): array {
        if (empty($users)) {
            $array = [1];
            $is_int = true;
            $count = 1;
            $search_for = '?';
        } else {
            $array = $users;
            $is_int = is_numeric($array[0]);
            $count = count($array);
            $in = str_repeat('?,', $count - 1).'?';
            $search_for = $is_int ? "id IN ($in)" : "email IN ($in) OR username IN ($in)";
        }

        $sql = "SELECT user.*,
                (SELECT COUNT(DISTINCT(following.followed)) FROM following WHERE following.follower = user.id) as following,
                (SELECT COUNT(DISTINCT(following.follower)) FROM following WHERE following.followed = user.id) as followers
                FROM user
                WHERE $search_for
                ORDER BY username"
                .($limit > 0? " LIMIT ? OFFSET ?" : "");
        $stmt = $this->db->prepare($sql);

        $is_int ?: $count *= 2;
        $types = str_repeat($is_int ? 'i' : 's', $count).($limit > 0 ? 'ii' : '');
        $params = [...($is_int ? $array : array_merge($array,$array))];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get a user given its id, email or username
     * @param int|string $user can be an id, email or username of the user
     * @return array|null array with the user data, null if the user doesn't exist
     */
    public function getUser(int|string $user): array|null {
        return $this->getUsers([$user], limit: 1)[0] ?? null;
    }

    /**
     * Update an existing user's data
     * The values left null will not be updated
     * @param int|string $user id, email or username of the user
     * @param string|null $email new email of the user
     * @param string|null $password new password of the user
     * @param string|null $username new username of the user
     * @param string|null $bio new bio of the user
     * @return bool true if the user was updated, false otherwise
     */
    public function updateUser(int|string $user, string $email = null, string $password = null, string $username = null, string $bio = null): bool {
        $user = $this->getUser($user);
        if ($user == null) {
            return false;
        }
        $sql = 'UPDATE IGNORE user SET email = ?, password = ?, username = ?, bio = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $params = [$email ?? $user['email'], $password ? password_hash($password, PASSWORD_DEFAULT) : $user['password'], $username ?? $user['username'], $bio ?? $user['bio'], $user['id']];
        $stmt->bind_param('ssssi', ...$params);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Delete an existing user
     * @param int|string $user id, email or username of the user to delete
     * @return bool false if the user doesn't exist, true otherwise
     */
    public function deleteUser(int|string $user): bool {
        $user = $this->getUser($user);
        if ($user == null) {
            return false;
        }

        $sql = 'DELETE FROM user WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get a user given its email and password, used for logging in
     * @param string $email email of the user
     * @param string $password password of the user
     * @return array|null array with the user data, null if the credentials are wrong
     */
    public function logUser(string $email, string $password): array|null {
        $sql = 'SELECT user.*,
                (SELECT COUNT(DISTINCT(following.followed)) FROM following WHERE following.follower = user.id) as following,
                (SELECT COUNT(DISTINCT(following.follower)) FROM following WHERE following.followed = user.id) as followers
                FROM user
                WHERE email = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user && password_verify($password, $user['password']) ? $user : null;
    }

    /**
     * Follow a user, if the user is already followed by the follower, nothing happens
     * @param int|string $follower id or email or username of the follower
     * @param int|string $followed id or email or username of the followed
     * @return bool true if the user was followed, false otherwise
     */
    public function followUser(int|string $follower, int|string  $followed): bool {
        $follower = $this->getUser($follower);
        $followed = $this->getUser($followed);
        if ($follower == null || $followed == null) {
            return false;
        }

        $sql = 'INSERT IGNORE INTO following (follower, followed) VALUES (?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $follower['id'], $followed['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Unfollow a user, if the user is not followed by the follower, nothing happens
     * @param int|string $follower id or email or username of the follower
     * @param int|string $followed id or email or username of the followed
     * @return bool true if the user was unfollowed, false otherwise
     */
    public function unfollowUser(int|string $follower, int|string $followed): bool {
        $follower = $this->getUser($follower);
        $followed = $this->getUser($followed);
        if ($follower == null || $followed == null) {
            return false;
        }

        $sql = 'DELETE FROM following WHERE follower = ? AND followed = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $follower['id'], $followed['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get the followers of a user
     * @param int|string $user id or email or username of the user
     * @param int $limit maximum number of users to return (return size could be less)
     * @param int $offset offset for pagination
     * @return array array of users that follow the given user
     */
    public function getFollowers(int|string $user, int $limit = 0, int $offset = 0): array {
        $user = $this->getUser($user);
        if ($user == null) {
            return [];
        }

        $sql = 'SELECT user.*, (SELECT COUNT(*) > 0 FROM following WHERE follower = ? AND followed = user.id) as following
                FROM user
                WHERE user.id IN (SELECT follower FROM following WHERE followed = ?)'
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);
        $types = 'ii'.($limit > 0 ? 'ii' : '');
        $params = [$user['id'], $user['id']];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    /**
     * Get the users followed by a user
     * @param int|string $user id or email or username of the user
     * @param int $limit maximum number of users to return (return size could be less)
     * @param int $offset offset for pagination
     * @return array array of users followed by the given user
     */
    public function getFollowed(int|string $user, int $limit = 0, int $offset = 0): array {
        $user = $this->getUser($user);
        if ($user == null) {
            return [];
        }

        $sql = 'SELECT *, 1 as following
                FROM user
                WHERE user.id IN (SELECT followed FROM following WHERE follower = ?)'
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);
        $types = 'i'.($limit > 0 ? 'ii' : '');
        $params = [$user['id']];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the number of followers of a user
     * @param int|string $user id or email or username of the user
     * @return int number of followers of the given user
     */
    public function getFollowersCount(int|string $user): int {
        $user = $this->getUser($user);
        if ($user == null) {
            return 0;
        }

        $sql = 'SELECT COUNT(*) FROM following WHERE followed = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] ?? 0;
    }

    /**
     * Get the number of users followed by a user
     * @param int|string $user id or email or username of the user
     * @return int number of users followed by the given user
     */
    public function getFollowedCount(int|string $user): int {
        $user = $this->getUser($user);
        if ($user == null) {
            return 0;
        }

        $sql = 'SELECT COUNT(*) FROM following WHERE follower = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] ?? 0;
    }

    /**
     * Check if a user is followed by another user
     * @param int|string $follower id or email or username of the follower
     */
    public function isFollowing(int|string $follower, int|string $followed): bool {
        $follower = $this->getUser($follower);
        $followed = $this->getUser($followed);
        if ($follower == null || $followed == null) {
            return false;
        }

        $sql = 'SELECT COUNT(*) FROM following WHERE follower = ? AND followed = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $follower['id'], $followed['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] > 0;
    }

    ////////////////////////////////////
    // Notifications related queries  //
    ////////////////////////////////////

    /**
     * Add a notification for a specific user to the database
     * @param int|string $receiver id or email or username of the user to send the notification to
     * @param int|string $sender id or email or username of the user that sent the notification
     * @param string $content content of the notification
     * @param string|null $type type of the notification, either 'post' or 'comment'
     * @param int|null $id id of the post or comment depending on the type, null if it is a generic notification
     * @return bool true if the notification was added, false if there was an error (user not found, post not found, etc.)
     */
    public function addNotification(int|string $receiver, int|string $sender, string $content, string $type = null, int $id = null): bool {
        $receiver = $this->getUser($receiver);
        $sender = $this->getUser($sender);
        if ($receiver == null || $sender == null) {
            return false;
        }

        $sql = 'INSERT IGNORE INTO notification (receiver, sender, content, date'
                .($type == 'post' ? ', post)' : ($type == 'comment' ? ', comment)' : ')'))
                .' VALUES (?, ?, ?, NOW()'
                .($type == 'post' || $type == 'comment' ? ', ?)' : ')');
        $stmt = $this->db->prepare($sql);
        $types = 'ii'.($type == 'post' || $type == 'comment' ? 'i' : '');
        $params = [$receiver['id'], $sender['id'], $content];
        if ($type == 'post' || $type == 'comment') array_push($params, $id);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get the notifications of a user
     * @param int|string $user id or email or username of the user
     * @param int $limit amount of notifications (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no notifications for that user
     */
    public function getUserNotifications(int|string $user, int $limit = 0, int $offset = 0): array {
        $user = $this->getUser($user);
        if ($user == null) {
            return [];
        }

        $sql = 'SELECT * FROM notification
                WHERE receiver = ?
                ORDER BY date DESC'
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);
        $types = 'i'.($limit > 0 ? 'ii' : '');
        $params = [$user['id']];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Mark all notifications of a user as read
     * @param int|string $user id or email or username of the user
     * @return bool true if the notifications were marked as read, false if there was an error
     */
    public function readAllNotifications(int|string $user): bool {
        $user = $this->getUser($user);
        if ($user == null) {
            return false;
        }

        $sql = 'UPDATE notification SET seen = 1 WHERE receiver = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Get the number of unread notifications of a user
     * @param int|string $user id or email or username of the user
     * @return int number of unread notifications
     */
    public function getUnreadNotificationsCount(int|string $user): int {
        $user = $this->getUser($user);
        if ($user == null) {
            return 0;
        }

        $sql = 'SELECT COUNT(*) FROM notification WHERE receiver = ? AND seen = 0';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] ?? 0;
    }



    ////////////////////////////////
    // Community related queries  //
    ////////////////////////////////

    /**
     * Create a new community on the database
     * @param int|string $author the id, email or username of user creating the community
     * @param string $communityName the name of the community
     * @param string $description the description of the community
     * @return bool true if the community was created, false otherwise
     */
    public function addCommunity(int|string $author, string $communityName, string $description): bool {
        $user = $this->getUser($author);
        if ($user == null || empty($communityName) || empty($description)) {
            return false;
        }

        $sql = 'INSERT IGNORE INTO community (author, name, description, creation_date) VALUES (?, ?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iss', $user['id'], $communityName, $description);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get communities from a list of ids or names
     * @param int[]|string[] $communities the ids or names of the communities to get, if null get all communities
     * @param int $limit amount of communities (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no communities with the given ids or names
     */
    public function getCommunities(array $communities = null, $limit = 0, $offset = 0): array {
        if (empty($communities)) {
            $array = [1];
            $is_int = true;
            $count = 1;
            $search_for = '?';
        } else {
            $array = $communities;
            $is_int = is_numeric($array[0]);
            $count = count($array);
            $in = str_repeat('?,', $count - 1).'?';
            $search_for = ($is_int ? 'community.id' : 'community.name')." IN ($in)";
        }

        $sql = "SELECT community.id, user.username as author, community.name, description, community.creation_date,
                (SELECT COUNT(DISTINCT(participation.user)) FROM participation WHERE participation.community = community.id) as participating
                FROM community JOIN user ON community.author = user.id
                WHERE $search_for"
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat($is_int ? 'i' : 's', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get a community from its id or name
     * @param int|string $community the id or name of the community to get
     * @return array|null null if there is no community with the given id or name
     */
    public function getCommunity(int|string $community): array|null {
        return $this->getCommunities([$community], limit: 1)[0] ?? null;
    }

    /**
     * Update an existing community
     * @param int|string $community the id or name of the community to update
     * @param string|null $description the new description of the community
     * @return bool true if the community was updated, false otherwise
     */
    public function updateCommunity(int|string $community, string $description = null): bool {
        $community = $this->getCommunity($community);
        if ($community == null) {
            return false;
        }

        $sql = 'UPDATE community SET description = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $params = [$description ?? $community['description'], $community['id']];
        $stmt->bind_param('si', ...$params);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Delete an existing community
     * @param int|string $community the id or name of the community to delete
     * @return bool true if the community was deleted, false otherwise
     */
    public function deleteCommunity(int|string $community): bool {
        $community = $this->getCommunity($community);
        if ($community == null) {
            return false;
        }

        $sql = 'DELETE FROM community WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $community['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get random communities
     * @param int $limit amount of communities (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no communities
     */
    public function getRandomCommunities($limit = 10, $offset = 0): array {
        $sql = 'SELECT community.id, user.username as author, community.name, description, community.creation_date,
                (SELECT COUNT(DISTINCT(participation.user)) FROM participation WHERE participation.community = community.id) as participating
                FROM community JOIN user ON community.author = user.id
                ORDER BY RAND()
                LIMIT ? OFFSET ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Check if a user is participating in a community
     * @param int|string $user the id, username or email of the user
     * @param int|string $community the id or name of the community
     * @return bool true if the user is participating in the community, false otherwise
     */
    public function isParticipating(int|string $user, int|string $community): bool {
        $user = $this->getUser($user);
        $community = $this->getCommunity($community);
        if ($user == null || $community == null) {
            return false;
        }

        $sql = 'SELECT COUNT(*) FROM participation WHERE user = ? AND community = ? AND date_left IS NULL';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $user['id'], $community['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] > 0;
    }

    /**
     * Make a user participate in a community
     * @param int|string $user the id, username or email of the user
     * @param int|string $community the id or name of the community
     * @return bool true if the user is participating in the community, false otherwise
     */
    public function joinCommunity(int|string $user, int|string $community): bool {
        $isAlreadyParticipating = $this->isParticipating($user, $community);
        $user = $this->getUser($user);
        $community = $this->getCommunity($community);
        if ($user == null || $community == null || $isAlreadyParticipating) {
            return false;
        }

        $sql = 'INSERT INTO participation (user, community, date_joined) VALUES (?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $user['id'], $community['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Make a user leave a community
     * @param int|string $user the id, username or email of the user
     * @param int|string $community the id or name of the community
     * @param string $reason the reason why the user left the community
     * @return bool true if the user is participating in the community, false otherwise
     */
    public function leaveCommunity(int|string $user, int|string $community, string $reason = 'no reason given'): bool {
        $isAlreadyParticipating = $this->isParticipating($user, $community);
        $user = $this->getUser($user);
        $community = $this->getCommunity($community);
        if ($user == null || $community == null || !$isAlreadyParticipating) {
            return false;
        }

        $sql = 'UPDATE participation SET date_left = NOW(), reason_left = ?
                WHERE user = ? AND community = ? AND date_left IS NULL
                ORDER BY date_joined DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sii', $reason, $user['id'], $community['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get the amount of users participating in a community
     * @param int|string $community the id or name of the community
     * @return int the amount of users participating in the community
     */
    public function getCommunityUserCount(int|string $community): int {
        $community = $this->getCommunity($community);
        if ($community == null) {
            return 0;
        }

        $sql = 'SELECT COUNT(DISTINCT user) FROM participation WHERE community = ? AND date_left IS NULL';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $community['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] ?? 0;
    }

    /**
     * Get the communities a user is participating in
     * @param int|string $user the id, email or username of the user
     * @param int $limit amount of communities (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if the user is not participating in any community
     */
    public function getParticipatingCommunities(int|string $user, $limit = 0, $offset = 0): array {
        $user = $this->getUser($user);
        if ($user == null) {
            return [];
        }

        $sql = 'SELECT DISTINCT(community)
                FROM participation
                WHERE user = ? AND date_left IS NULL';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $this->getCommunities(array_column($result, 'community'), $limit, $offset);
    }



    ///////////////////////////
    // Post related queries  //
    ///////////////////////////

    /**
     * Create a new post on the database
     * @param int $author id, email or username of the user who created the post
     * @param int $community id or name of the community where the post will be posted
     * @param string $title title of the post
     * @param string $content content of the post
     * @return bool false if keys checks fail, true otherwise
     */
    public function addPost(int|string $author, int|string $community, string $title, string $content): bool {
        $author = $this->getUser($author);
        $community = $this->getCommunity($community);
        $id = $this->getMaxId() + 1;
        if ($author == null || $community == null || empty($title) || empty($content)) {
            return false;
        }

        $sql = "INSERT IGNORE INTO post (id,author,community,title,content,creation_date) VALUES ($id,?,?,?,?,NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiss', $author['id'], $community['id'], $title, $content);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get posts from a list of postIDs
     * @param int[] $postIds list of post ids
     * @param int $limit amount of posts (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no posts with those ids
     */
    public function getPosts(array $postIds, int $limit = 0, int $offset = 0): array {
        $array = $postIds;
        if (empty($array)) {
            return [];
        }

        $count = count($array);
        $in = str_repeat('?,', $count - 1).'?';
        $search_for = "post.id IN ($in)";
        $sql = "SELECT post.id, user.username as author, community.name as community, post.title, post.content, post.attachment, post.creation_date, post.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_post WHERE post = post.id) as vote,
                (SELECT COUNT(*) FROM comment WHERE post = post.id) as comments
                FROM (post JOIN user ON post.author = user.id) JOIN community ON post.community = community.id
                WHERE $search_for".($limit > 0 ? " LIMIT ? OFFSET ?" : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat('i', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get a post given its id
     * @param int $postId id of the post to get
     * @return array|null null if the post doesn't exist
     */
    public function getPost(int $postId): array|null {
        return $this->getPosts([$postId], limit: 1)[0] ?? null;
    }

    /**
     * Update an existing post's title and/or content
     * The values left null will not be updated
     * @param int $postId id of the post to update
     * @param string|null $title new title
     * @param string|null $content new content
     * @return bool false if the post doesn't exist, true otherwise
     */
    public function updatePost(int $postId, string $title = null, string $content = null): bool {
        $post = $this->getPost($postId);
        if ($post == null) {
            return false;
        }

        $sql = 'UPDATE post SET title = ?, content = ?, edited = 1 WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $params = [$title ?? $post['title'], $content ?? $post['content'], $post['id']];
        $stmt->bind_param('ssi', ...$params);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Delete an existing post
     * @param int $postId id of the post to delete
     * @return bool false if the post doesn't exist, true otherwise
     */
    public function deletePost(int $postId): bool {
        $post = $this->getPost($postId);
        if ($post == null) {
            return false;
        }

        $sql = 'DELETE FROM post WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $post['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get random posts from every community
     * @param int $limit amount of posts (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no posts
     */
    public function getRandomPosts(int $limit = 10, int $offset = 0): array {
        $sql = 'SELECT post.id, user.username as author, community.name as community, post.title, post.content, post.attachment, post.creation_date, post.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_post WHERE post = post.id) as vote,
                (SELECT COUNT(*) FROM comment WHERE post = post.id) as comments
                FROM (post JOIN user ON post.author = user.id) JOIN community ON post.community = community.id
                ORDER BY RAND()
                LIMIT ? OFFSET ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the latest posts from a list of communityIds
     * If both arrays are passed, the ids will be used
     * if both are null, the result will be an empty array
     * @param int[]|string[] $communities list of community ids or names
     * @param int $limit amount of posts (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no posts from those communities
     */
    public function getPostsByCommunities(array $communities, int $limit = 0, int $offset = 0): array {
        if (empty($communities)) {
            return [];
        }
        $array = array_column($this->getCommunities($communities), 'id');

        $count = count($array);
        $in  = str_repeat('?,', $count-1).'?';
        $sql = "SELECT post.id, user.username as author, community.name as community, post.title, post.content, post.attachment, post.creation_date, post.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_post WHERE post = post.id) as vote,
                (SELECT COUNT(*) FROM comment WHERE post = post.id) as comments
                FROM (post JOIN user ON post.author = user.id) JOIN community ON post.community = community.id
                WHERE community.id IN ($in)
                ORDER BY post.creation_date DESC"
                .($limit > 0 ? " LIMIT ? OFFSET ?" : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat('i', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the latest posts from a community
     * @param int|string $community id or name of the community
     * @param int $limit amount of posts (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no posts from that community
     */
    public function getPostsByCommunity(int|string $community, int $limit = 0, int $offset = 0): array {
        return $this->getPostsByCommunities([$community], $limit, $offset);
    }

    /**
     * Get the latest posts from a list of users
     * @param int[]|string[] $users list of user ids or names
     * @param int $limit amount of posts (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no posts from those users
     */
    public function getPostsByUsers(array $users, int $limit = 0, int $offset = 0): array {
        if (empty($users)) {
            return [];
        }
        $array = array_column($this->getUsers($users), 'id');

        $count = count($array);
        $in  = str_repeat('?,', $count-1).'?';
        $sql = "SELECT post.id, user.username as author, community.name as community, post.title, post.content, post.attachment, post.creation_date, post.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_post WHERE post = post.id) as vote,
                (SELECT COUNT(*) FROM comment WHERE post = post.id) as comments
                FROM (post JOIN user ON post.author = user.id) JOIN community ON post.community = community.id
                WHERE user.id IN ($in)
                ORDER BY post.creation_date DESC"
                .($limit > 0 ? " LIMIT ? OFFSET ?" : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat('i', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the latest posts from a user
     * @param int|string $user id, email or username of the user
     * @param int $limit amount of posts (return size could be less)
     * @param int $offset offset for pagination
     * @return array empty if there are no posts from that user
     */
    public function getPostsByUser(int|string $user, int $limit = 0, int $offset = 0): array {
        return $this->getPostsByUsers([$user], $limit, $offset);
    }

    /**
     * Get the vote of a user for a post
     * @param int|string $user id, name or email of the user
     * @param int $postId id of the post
     * @return int vote value (0 = no vote, 1 = upvote, -1 = downvote)
     */
    public function getUserPostVote(int|string $user, int $postId): int {
        $user = $this->getUser($user);
        $post = $this->getPost($postId);
        if ($user == null || $post == null) {
            return 0;
        }

        $sql = 'SELECT vote
                FROM vote_post
                WHERE user = ? AND post = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $user['id'], $post['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['vote'] ?? 0;
    }

    /**
     * Set a vote for a post
     * If the same vote is already in the database, the vote is set back to 0
     * @param int|string $user id, name or email of the user
     * @param int $postId id of the post to vote
     * @param int $vote vote value (0 = no vote, 1 = upvote, -1 = downvote)
     * @return bool false if the post or user don't exist, true otherwise
     */
    public function votePost(int|string $user, int $postId, int $vote = 0): bool {
        $user = $this->getUser($user);
        $post = $this->getPost($postId);
        if ($user == null || $post == null) {
            return false;
        }

        // check if the user already voted on this post
        $currentVote = $this->getUserPostVote($user['id'], $post['id']);
        // if the vote passed is the same as the current vote, set the vote to 0
        $vote = $currentVote == $vote ? 0 : $vote;

        if ($vote == 0) {
            //delete the vote on the database
            $sql = 'DELETE FROM vote_post WHERE user = ? AND post = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $user['id'], $post['id']);
        } else {
            //insert or update the vote on the database
            $sql = 'INSERT INTO vote_post (user, post, vote) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('iiii', $user['id'], $post['id'], $vote, $vote);
        }
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get the rating (sum of votes) of a post
     * @param int $postId id of the post
     * @return int sum of upvotes and downvotes, 0 if the post doesn't exist
     */
    public function getPostVote(int $postId): int {
        $sql = 'SELECT SUM(vote) FROM vote_post WHERE post = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['SUM(vote)'] ?? 0;
    }



    //////////////////////////////
    // Comment related queries  //
    //////////////////////////////

    /**
     * Add a comment to a post by a user
     * @param int $post the id of the post
     * @param int|string $author the id, username or email of the user who wrote the comment
     * @param string $content the content of the comment
     * @return bool true if the comment was added, false otherwise
     */
    public function addComment(int $post, int|string $author, string $content): bool {
        $author = $this->getUser($author);
        $id = $this->getMaxId() + 1;
        if ($post == null || $author == null || empty($content)) {
            return false;
        }

        $sql = "INSERT INTO comment (id,author,post,content,creation_date) VALUES ($id,?,?,?,NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis', $author['id'], $post, $content);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get comments from a list of ids
     * @param int[] $commentIds the ids of the comments
     * @param int $limit amount of comments (return size could be less)
     * @param int $offset offset for pagination
     * @return array the comments with the given ids
     */
    public function getComments(array $commentIds, int $limit = 0, int $offset = 0): array {
        $array = $commentIds;
        if (empty($array)) {
            return [];
        }

        $count = count($array);
        $in = str_repeat('?,', $count - 1).'?';
        $search_for = "comment.id IN ($in)";
        $sql = "SELECT comment.id, user.username as author, comment.content, comment.creation_date, comment.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_comment WHERE comment = comment.id) as vote
                FROM comment JOIN user ON comment.author = user.id
                WHERE $search_for"
                .($limit > 0 ? " LIMIT ? OFFSET ?" : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat('i', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get a comment by its id
     * @param int $commentId the id of the comment
     * @return array|null the comment with the given id, null if it doesn't exist
     */
    public function getComment(int $commentId): array|null {
        return $this->getComments([$commentId], 1)[0] ?? null;
    }

    /**
     * Update an existing comment's content
     * @param int $commentId the id of the comment
     * @param string $content the new content of the comment
     * @return bool true if the comment was updated, false otherwise
     */
    public function updateComment(int $commentId, string $content): bool {
        $comment = $this->getComment($commentId);
        if ($comment == null || empty($content)) {
            return false;
        }

        $sql = 'UPDATE comment SET edited = 1, content = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $content, $commentId);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Delete an existing comment
     * @param int $commentId the id of the comment
     * @return bool true if the comment was deleted, false otherwise
     */
    public function deleteComment(int $commentId): bool {
        $comment = $this->getComment($commentId);
        if ($comment == null) {
            return false;
        }

        $sql = 'DELETE FROM comment WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $comment['id']);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get comments of multiple posts
     * @param int[] $postIds the ids of the posts
     * @param int $limit amount of comments (return size could be less)
     * @param int $offset offset for pagination
     * @return array the comments
     */
    public function getCommentsByPosts(array $postIds, int $limit = 0, int $offset = 0): array {
        $array = $postIds;
        if (empty($array)) {
            return [];
        }

        $count = count($array);
        $in = str_repeat('?,', $count - 1).'?';
        $search_for = "comment.post IN ($in)";
        $sql = "SELECT comment.id, user.username as author, comment.content, comment.creation_date, comment.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_comment WHERE comment = comment.id) as vote
                FROM comment JOIN user ON comment.author = user.id
                WHERE $search_for
                ORDER BY vote DESC, comment.creation_date DESC"
                .($limit > 0 ? " LIMIT ? OFFSET ?" : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat('i', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get comments of a post
     * @param int $postId the id of the post
     * @param int $limit amount of comments (return size could be less)
     * @param int $offset offset for pagination
     * @return array the comments
     */
    public function getCommentsByPost(int $postId, int $limit = 0, int $offset = 0): array {
        return $this->getCommentsByPosts([$postId], $limit, $offset);
    }

    /**
     * Get the number of comments for a post
     * @param int $postId id of the post
     * @return int number of comments, 0 if the post doesn't exist
     */
    public function getCommentCountByPost(int $postId): int {
        $sql = 'SELECT COUNT(*) FROM comment WHERE post = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] ?? 0;
    }

    /**
     * Get comments made from a list of users
     * @param int[]|string[] $users the ids, usernames or emails of the users
     * @param int $limit amount of comments (return size could be less)
     * @param int $offset offset for pagination
     * @return array the comments
     */
    public function getCommentsByUsers(array $users, int $limit = 0, int $offset = 0): array {
        $array = array_column($this->getUsers($users), 'id');
        if (empty($array)) {
            return [];
        }

        $count = count($array);
        $in = str_repeat('?,', $count - 1).'?';
        $search_for = "author IN ($in)";
        $sql = "SELECT comment.id, user.username as author, comment.content, comment.creation_date, comment.edited,
                (SELECT COALESCE(SUM(vote),0) FROM vote_comment WHERE comment = comment.id) as vote
                FROM comment JOIN user ON comment.author = user.id
                WHERE $search_for
                ORDER BY creation_date DESC"
                .($limit > 0 ? " LIMIT ? OFFSET ?" : '');
        $stmt = $this->db->prepare($sql);

        $types = str_repeat('i', $count).($limit > 0 ? 'ii' : '');
        $params = [...$array];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get comments made from a user
     * @param int|string $user the id, username or email of the user
     * @param int $limit amount of comments (return size could be less)
     * @param int $offset offset for pagination
     * @return array the comments
     */
    public function getCommentsByUser(int|string $user, int $limit = 0, int $offset = 0): array {
        return $this->getCommentsByUsers([$user], $limit, $offset);
    }

    /**
     * Get the number of comments made from a user
     * @param int|string $user the id, username or email of the user
     * @return int number of comments, 0 if the user doesn't exist
     */
    public function getCommentCountByUser(int|string $user): int {
        $user = $this->getUser($user);
        if ($user == null) {
            return 0;
        }

        $sql = 'SELECT COUNT(*) FROM comment WHERE author = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['COUNT(*)'] ?? 0;
    }

    /**
     * Get the vote of a user for a comment
     * @param int|string $user the id, username or email of the user
     * @param int $commentId the id of the comment
     * @return int vote value (0 = no vote, 1 = upvote, -1 = downvote)
     */
    public function getUserCommentVote(int|string $user, int $commentId): int {
        $user = $this->getUser($user);
        $comment = $this->getComment($commentId);
        if ($user == null || $comment == null) {
            return 0;
        }

        $sql = 'SELECT vote FROM vote_comment WHERE user = ? AND comment = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $user['id'], $comment['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['vote'] ?? 0;
    }


    /**
     * Set a vote for a comment
     * If the same vote is already in the database, the vote is set back to 0
     * @param int|string $user id, name or email of the user
     * @param int $commentId id of the comment to vote
     * @param int $vote vote value (0 = no vote, 1 = upvote, -1 = downvote)
     * @return bool false if the comment or user don't exist, true otherwise
     */
    public function voteComment(int|string $user, int $commentId, int $vote): bool {
        $user = $this->getUser($user);
        $comment = $this->getComment($commentId);
        if ($user == null || $comment == null) {
            return false;
        }

        $currentVote = $this->getUserCommentVote($user['id'], $commentId);
        $vote = $currentVote == $vote ? 0 : $vote;

        if ($vote == 0) {
            $sql = 'DELETE FROM vote_comment WHERE user = ? AND comment = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $user['id'], $comment['id']);
        } else {
            $sql = 'INSERT INTO vote_comment (user, comment, vote) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('iiii', $user['id'], $comment['id'], $vote, $vote);
        }
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Get the rating (sum of votes) of a comment
     * @param int $commentId the id of the comment
     * @return int sum of upvotes and downvotes, 0 if the comment doesn't exist
     */
    public function getCommentVote(int $commentId): int {
        $sql = 'SELECT SUM(vote) FROM vote_comment WHERE comment = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['SUM(vote)'] ?? 0;
    }



    /////////////////////////////
    // Search related queries  //
    /////////////////////////////

    /**
     * Search for usernames and emails given a string query
     * @param string $query the query to search for
     * @param int $limit amount of usernames (return size could be less)
     * @param int $offset offset for pagination
     * @return array the usernames by order of relevance
     */
    public function searchUsers(string $query, int $limit = 5, int $offset = 0): array {
        $queryLike = $query.'%';
        $sql = "SELECT DISTINCT(username)
                FROM user
                WHERE username LIKE ? OR email LIKE ?
                ORDER BY LOCATE(?, username) ASC, LOCATE(?, email) ASC"
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);
        $types = 'ssss'.($limit > 0 ? 'ii' : '');
        $params = [$queryLike, $queryLike, $query, $query];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    /**
     * Search for communities given a string query
     * @param string $query the query to search for
     * @param int $limit amount of communities (return size could be less)
     * @param int $offset offset for pagination
     * @return array the community names by order of relevance
     */
    public function searchCommunities(string $query, int $limit = 5, int $offset = 0): array {
        $queryLike = $query.'%';
        $sql = 'SELECT DISTINCT(name) FROM community WHERE name LIKE ?'
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);
        $types = 's'.($limit > 0 ? 'ii' : '');
        $params = [$queryLike];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Search for both users and communities given a string query
     * @param string $query the query to search for
     * @param int $limit amount of results (return size could be less)
     * @param int $offset offset for pagination
     * @return array the usernames and community names by order of relevance
     */
    public function searchUsersAndCommunities(string $query, int $limit = 5, int $offset = 0): array {
        $queryLike = $query.'%';
        $sql = "SELECT DISTINCT(username) AS name, 'profile' AS type
                FROM user
                WHERE username LIKE ? OR email LIKE ?
                UNION
                SELECT DISTINCT(name) AS name, 'community' AS type
                FROM community
                WHERE name LIKE ?
                ORDER BY LOCATE(?, name) ASC, LOCATE(?, name) ASC"
                .($limit > 0 ? ' LIMIT ? OFFSET ?' : '');
        $stmt = $this->db->prepare($sql);
        $types = 'sssss'.($limit > 0 ? 'ii' : '');
        $params = [$queryLike, $queryLike, $queryLike, $query, $query];
        if ($limit > 0) array_push($params, $limit, $offset);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}


