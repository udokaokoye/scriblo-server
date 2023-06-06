<?php

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
    public $publishDate;
    public $isHidden;
    public $createdAt;
    public $mediaFiles;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add Post
    public function addPost($postData)
    {
        try {
            $query = 'INSERT INTO ' . $this->table . ' (slug, authorId, title, summary, content, tags, publishDate, isHidden, createdAt, mediaFiles, coverImage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

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
            // Bind data
            $stmt->bindParam(1, $this->slug);
            $stmt->bindParam(2, $this->authorId);
            $stmt->bindParam(3, $this->title);
            $stmt->bindParam(4, $this->summary);
            $stmt->bindParam(5, $this->content);
            $stmt->bindParam(6, $this->tags);
            $stmt->bindParam(7, $this->publishDate);
            $stmt->bindParam(8, $this->isHidden);
            $stmt->bindParam(9, $this->createdAt);
            $stmt->bindParam(10, $this->mediaFiles);
            $stmt->bindParam(11, $this->coverImage);



            // Execute query
            if ($stmt->execute()) {

                if ($this->isHidden == 0) {
                    $this->updatePostTagRelationshipTable($this->conn->lastInsertId());
                    return true;
                }

                return true;
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

        if (isset($categories)) {
            try {
                $query = "SELECT DISTINCT posts.*
                FROM posts
                JOIN post_tags ON posts.id = post_tags.postId
                JOIN tags ON post_tags.tagId = tags.id
                WHERE tags.id IN ($categories)";
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
                $query = 'SELECT * FROM ' . $this->table . ' WHERE isHidden = 0 ORDER BY publishDate DESC';
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

    public function updatePost($postData)
    {
        try {
            $query = 'UPDATE ' . $this->table . ' SET title = ?, content = ?, tags = ?, publishDate = ?, isHidden = ?, mediaFiles = ? WHERE id = ?';
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->title = htmlspecialchars(strip_tags($postData['title']));
            $this->content = htmlspecialchars(strip_tags($postData['content']));
            $this->tags = htmlspecialchars(strip_tags($postData['tags']));
            $this->publishDate = htmlspecialchars(strip_tags($postData['publishDate']));
            $this->isHidden = htmlspecialchars(strip_tags($postData['isHidden']));
            $this->mediaFiles = htmlspecialchars(strip_tags($postData['mediaFiles']));
            $this->id = htmlspecialchars(strip_tags($postData['id']));

            // Bind data
            $stmt->bindParam(1, $this->title);
            $stmt->bindParam(2, $this->content);
            $stmt->bindParam(3, $this->tags);
            $stmt->bindParam(4, $this->publishDate);
            $stmt->bindParam(5, $this->isHidden);
            $stmt->bindParam(6, $this->mediaFiles);
            $stmt->bindParam(7, $this->id);

            // Execute query
            if ($stmt->execute()) {
                if ($this->isHidden == 0) {
                    $this->updatePostTagRelationshipTable($this->id);
                    return true;
                }
                return true;
            }
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function updatePostTagRelationshipTable($postId)
    {
        try {

            $query = 'INSERT INTO post_tags (postId, tagId) VALUES (?, ?)';
            $stmt = $this->conn->prepare($query);
            $tags = explode(',', $this->tagsIDs);
            foreach ($tags as $tag) {
                $stmt->execute([$postId, $tag]);
            }
            return true;
        } catch (Exception $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }
}
