<?php
include_once __DIR__ . '/../utils/Crypt.php';
class Post
{
    // DB stuff
    private $conn;
    private $table = 'posts';

    // Post Properties
    public $id;
    public $slug;
    public $authorId;
    public $title;
    public $summary;
    public $coverImage;
    public $content;
    public $tags;
    public $tagsIDs;
    public $readTime;
    public $publishDate;
    public $isHidden;
    public $createdAt;
    public $mediaFiles;
    public $authorUsername;
    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add Post
    public function addPost($postData)
    {
        try {
            $query = 'INSERT INTO ' . $this->table . ' (slug, authorId, title, summary, content, tags, readTime, publishDate, isHidden, createdAt, mediaFiles, coverImage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->slug = htmlspecialchars(strip_tags($postData['slug']));
            $this->authorId = htmlspecialchars(strip_tags($postData['authorId']));
            $this->title = htmlspecialchars(strip_tags($postData['title']));
            $this->content = $postData['content'];
            $this->tags = htmlspecialchars(strip_tags($postData['tags']));
            $this->publishDate = htmlspecialchars(strip_tags($postData['publishDate']));
            $this->isHidden = htmlspecialchars(strip_tags($postData['isHidden']));
            $this->createdAt = htmlspecialchars(strip_tags($postData['createdAt']));
            $this->mediaFiles = serialize($postData['mediaFiles']);
            $this->tagsIDs = htmlspecialchars(strip_tags($postData['tagsIDs']));
            $this->coverImage = htmlspecialchars(strip_tags($postData['coverImage']));
            $this->summary = htmlspecialchars(strip_tags($postData['summary']));
            $this->authorUsername = htmlspecialchars(strip_tags($postData['username']));
            $this->readTime = htmlspecialchars(strip_tags($postData['readTime']));
            // Bind data
            $stmt->bindParam(1, $this->slug);
            $stmt->bindParam(2, $this->authorId);
            $stmt->bindParam(3, $this->title);
            $stmt->bindParam(4, $this->summary);
            $stmt->bindParam(5, $this->content);
            $stmt->bindParam(6, $this->tags);
            $stmt->bindParam(7, $this->readTime);
            $stmt->bindParam(8, $this->publishDate);
            $stmt->bindParam(9, $this->isHidden);
            $stmt->bindParam(10, $this->createdAt);
            $stmt->bindParam(11, $this->mediaFiles);
            $stmt->bindParam(12, $this->coverImage);



            // Execute query
            if ($stmt->execute()) {
                $lastId = $this->conn->lastInsertId();

                if ($this->isHidden == 0) {
                    $this->updatePostTagRelationshipTable($lastId, $postData['tagsIDs']);
                    // return true;
                }
                // query update slug with id
                $upadatedSlug = '';
                if ($this->isHidden == 0) {
                    $upadatedSlug = $this->slug . '-' . $lastId;
                } else {
                    $upadatedSlug = $this->authorUsername . '-' . $lastId;
                }
                $query = "UPDATE posts SET slug = ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $upadatedSlug);
                $stmt->bindParam(2, $lastId);
                $stmt->execute();

                if (isset($postData['setupPreviewLink']) && $postData['setupPreviewLink'] == 1) {
                    $previewCode = rand(10000, 99999);
                    $hashedPreviewCode = CryptHelper::encrypt($previewCode);
                    $this->setupPreviewLink($lastId, $this->authorId, $postData['previewSlug'], $hashedPreviewCode, $this->createdAt);
                }

                if (isset($postData['setupPreviewLink'])) {
                    return ["previewCode" => $previewCode, "previewUrl" => $postData['previewSlug']];
                } else {
                    return $lastId;
                }
            }
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Get Posts
    public function getPosts($categories)
    {

        if (isset($categories) && $categories != 'all') {
            try {
                $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
                FROM posts
                JOIN post_tags ON posts.id = post_tags.postId
                JOIN users u ON posts.authorId = u.id
                JOIN tags ON post_tags.tagId = tags.id
                WHERE tags.id IN ($categories) ORDER BY posts.createdAt DESC";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        } else {
            try {
                $query = " SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
                FROM posts
                JOIN post_tags ON posts.id = post_tags.postId
                JOIN users u ON posts.authorId = u.id ORDER BY posts.createdAt DESC
                ";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        }
    }

    public function getUserPosts($method, $identifier)
    {

        if ($method == 'username') {
            try {
                $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar, pr.slug AS previewSlug, pr.code AS previewCode 
                FROM posts
                JOIN users u ON posts.authorId = u.id
                LEFT JOIN previews pr ON posts.id = pr.postId
                WHERE u.username = ? ORDER BY posts.createdAt DESC";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $identifier);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        } else if ($method == 'id') {
            try {
                $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar, pr.slug AS previewSlug, pr.code AS previewCode 
                FROM posts
                JOIN users u ON posts.authorId = u.id
                LEFT JOIN previews pr ON posts.authorId = pr.authorId
                WHERE u.id = ? ORDER BY posts.createdAt DESC";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $identifier);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        }
    }

    public function getPost($slug)
    {
        try {
            $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.bio AS authorBio, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
            FROM posts
            -- JOIN post_tags ON posts.id = post_tags.postId
            JOIN users u ON posts.authorId = u.id
            -- JOIN tags ON post_tags.tagId = tags.id
            WHERE posts.slug = '$slug'";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function getPostById($articleId)
    {
        try {
            $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.bio AS authorBio, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
            FROM posts
            -- JOIN post_tags ON posts.id = post_tags.postId
            JOIN users u ON posts.authorId = u.id
            -- JOIN tags ON post_tags.tagId = tags.id
            WHERE posts.id = '$articleId'";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function searchPosts($searchQuery, $class)
    {
        if ($class == 'articles') {
            try {
                $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.verified AS authorVerified, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
                FROM posts
                JOIN post_tags ON posts.id = post_tags.postId
                JOIN users u ON posts.authorId = u.id
                JOIN tags ON post_tags.tagId = tags.id
                WHERE posts.title LIKE '%$searchQuery%' OR 
                posts.slug LIKE '%$searchQuery%' OR 
                tags.name LIKE '%$searchQuery%' ORDER BY posts.createdAt DESC";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        } else if ($class == 'people') {
            try {
                $query = "SELECT * FROM users WHERE name LIKE '%$searchQuery%' OR email ORDER BY createdAt DESC";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        } else if ($class == 'tags') {
            try {
                $query = "SELECT DISTINCT posts.*, u.name AS authorName, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
                FROM posts
                JOIN post_tags ON posts.id = post_tags.postId
                JOIN users u ON posts.authorId = u.id
                JOIN tags ON post_tags.tagId = tags.id
                WHERE tags.name LIKE '%$searchQuery%' ORDER BY posts.createdAt DESC";
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        } else {
            return null;
        }
    }

    public function updatePost($postData)
    {
        try {
            $query = 'UPDATE ' . $this->table . ' SET title = ?, content = ?, summary = ?, slug = ?, tags = ?, readTime = ?, isHidden = ?, coverImage = ? WHERE id = ?';
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->title = htmlspecialchars(strip_tags($postData['title']));
            $this->content = $postData['content'];
            $this->summary = htmlspecialchars(strip_tags($postData['summary']));
            $this->slug = htmlspecialchars(strip_tags($postData['slug']));
            $this->tags = htmlspecialchars(strip_tags($postData['tags']));
            $this->isHidden = htmlspecialchars(strip_tags($postData['isHidden']));
            $this->coverImage = htmlspecialchars(strip_tags($postData['coverImage']));
            $this->readTime = htmlspecialchars(strip_tags($postData['readTime']));
            $id = htmlspecialchars(strip_tags($postData['id']));

            // Bind data
            $stmt->bindParam(1, $this->title);
            $stmt->bindParam(2, $this->content);
            $stmt->bindParam(3, $this->summary);
            $stmt->bindParam(4, $this->slug);
            $stmt->bindParam(5, $this->tags);
            $stmt->bindParam(6, $this->readTime);
            $stmt->bindParam(7, $this->isHidden);
            $stmt->bindParam(8, $this->coverImage);
            $stmt->bindParam(9, $id);

            // Execute query
            if ($stmt->execute()) {
                if ($this->isHidden == 0) {
                    $this->deletePostTagRelationshipTable($id);
                    $this->updatePostTagRelationshipTable($id, $postData['tagsIDs']);
                    $upadatedSlug = '';
                    $upadatedSlug = $this->slug . '-' . $id;
                    $query = "UPDATE posts SET slug = ? WHERE id = ?";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(1, $upadatedSlug);
                    $stmt->bindParam(2, $id);
                    $stmt->execute();
                    return true;
                }

                if ($this->isHidden == 1) {
                    $this->deletePostTagRelationshipTable($id);
                }
                return true;
            }
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function updatePostTagRelationshipTable($postId, $postTags)
    {
        try {

            $query = 'INSERT INTO post_tags (postId, tagId) VALUES (?, ?)';
            $stmt = $this->conn->prepare($query);
            $tags = explode(',', $postTags);
            foreach ($tags as $tag) {
                $stmt->execute([$postId, $tag]);
            }
        } catch (Exception $e) {
            // check if error is duplicate entry
            if ($e->getCode() == 23000) {
                $this->deletePostTagRelationshipTable($postId);
                $this->updatePostTagRelationshipTable($postId, $postTags);
                return true;
            } else {

                echo ResponseHandler::sendResponse(500, $e->getMessage());
            }
            return;
        }
    }

    public function deletePostTagRelationshipTable($postId)
    {
        try {
            $query = 'DELETE FROM post_tags WHERE postId = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $postId);
            $stmt->execute();
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }


    // POST ACTIONS

    public function likePost($postID, $userID, $date)
    {
        try {
            $query = "INSERT INTO likes (postId, userId, createdAt) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$postID, $userID, $date]);
            return true;
        } catch (Exception $e) {
            // check if error is duplicate entry
            if ($e->getCode() == 23000) {
                $query = "DELETE FROM likes WHERE postId = ? AND userId = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$postID, $userID]);
                return true;
            }
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return false;
        }
    }

    public function commentPost($postID, $userID, $comment, $date, $replyID)
    {
        try {
            if ($replyID == null) {
                $query = "INSERT INTO comments (postId, userId, content, createdAt) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$postID, $userID, $comment, $date]);
                return true;
            } else {
                $query = "INSERT INTO comments (postId, userId, content, replyId, createdAt) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$postID, $userID, $comment, $replyID, $date]);
                return true;
            }
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return false;
        }
    }

    public function bookmarkPost($postID, $userID, $date)
    {
        try {
            $query = "INSERT INTO bookmarks (postId, userId, createdAt) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$postID, $userID, $date]);
            return true;
        } catch (Exception $e) {
            // check if error is duplicate entry
            if ($e->getCode() == 23000) {
                $query = "DELETE FROM bookmarks WHERE postId = ? AND userId = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$postID, $userID]);
                return true;
            }
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return false;
        }
    }

    public function getCommnets($postID)
    {
        try {
            $query = "SELECT comments.*, u.name AS authorName, u.verified AS authorVerified, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar  FROM `comments` JOIN users u ON comments.userID = u.id WHERE comments.postId = '$postID' ORDER BY comments.createdAt DESC ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {

            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return false;
        }
    }

    public function getBookmarks($userID)
    {
        try {
            $query = "SELECT DISTINCT bookmarks.id AS bookmarkId, posts.*, u.name AS authorName, u.username AS authorUsername, u.email AS authorEmail, u.avatar AS authorAvatar 
            FROM bookmarks JOIN posts ON bookmarks.postId = posts.id JOIN users u ON posts.authorId = u.id WHERE userID = '$userID' ORDER BY bookmarks.createdAt DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {

            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return false;
        }
    }

    public function getLikes($postID)
    {
        try {
            $query = "SELECT * FROM likes WHERE postId = '$postID'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function deleteComment($commentId)
    {
        try {
            $query = "DELETE FROM comments WHERE id = '$commentId' OR replyId = '$commentId' ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function deleteBookmark($bookmarkId)
    {
        try {
            $query = "DELETE FROM bookmarks WHERE id='$bookmarkId'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function deletePost($postId)
    {
        try {
            $query = "DELETE FROM posts WHERE id= ? ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $postId);
            $stmt->execute();
            // delete post tags
            $this->deletePostTagRelationshipTable($postId);
            return true;
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function setupPreviewLink($postID, $authorID, $slug, $code, $createdAt)
    {
        try {
            $query = "INSERT INTO `previews` (postId, authorId, slug, code, createdAt) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$postID, $authorID, $slug, $code, $createdAt]);
            return true;
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return false;
        }
    }

    public function checkIfCodeRequiredForPreview($previewSlug)
    {
        $query = "SELECT code, authorId, postId, u.name AS authorName FROM `previews` JOIN users u ON previews.authorId = u.id WHERE previews.slug = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$previewSlug]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPreviewCode($slug)
    {
        $query = "SELECT code, postId, u.username AS username FROM previews JOIN users u ON previews.authorId = u.id WHERE previews.slug = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$slug]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
