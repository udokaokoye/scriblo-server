<?php


class FeedAlgorithim
{
    public static $userInterests;

    public static function rankPosts($posts, $userInterests=null)
    {
        self::$userInterests = $userInterests;
        
        foreach ($posts as &$post) {
            $relevanceScore = self::calculateRelevanceScore($post);
            $post['relevance'] = $relevanceScore;
        }

        
        usort($posts, function ($a, $b) {
            return $b['relevance'] - $a['relevance'];
        });

        return $posts;
    }

    private static function calculateRelevanceScore($post)
    {
        $score = 0;

        $tagMatchWeight = 0.7;
        $popularityWeight = 0.3;

        $tagMatchScore = self::calculateTagMatchScore($post, self::$userInterests);
        $score += $tagMatchScore * $tagMatchWeight;

        $popularityScore = self::calculatePopularityScore($post);
        $score += $popularityScore * $popularityWeight;

        return $score;
    }

    private static function calculateTagMatchScore($post)
    {
        $postTags = explode(',', $post['tags']);
        $matchCount = 0;

        foreach ($postTags as $tag) {
            if (in_array($tag, self::$userInterests)) {
                $matchCount++;
            }
        }

        $matchScore = ($matchCount / count($postTags)) * 100;

        return $matchScore;
    }

    private static function calculatePopularityScore($post)
    {
        $viewsWeight = 0.5;
        $likesWeight = 0.3;
        $commentsWeight = 0.2;

        $views = $post['views'];
        $likes = $post['likes'];
        $comments = $post['comments'];

        $popularityScore = ($views * $viewsWeight) + ($likes * $likesWeight) + ($comments * $commentsWeight);

        return $popularityScore;
    }
}
