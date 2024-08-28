<?php

class TrendingAlgorithim {

    public static function rankPosts($posts, $userInterests=null)
    {
        
        foreach ($posts as &$post) {
            $relevanceScore = self::calculatePopularityScore($post);
            $post['relevance'] = $relevanceScore;
        }

        
        usort($posts, function ($a, $b) {
            return $b['relevance'] <=> $a['relevance'];
        });

        return $posts;
    }

    private static function calculatePopularityScore($post)
    {
        // $viewsWeight = 0.5;
        $likesWeight = 0.3;
        $commentsWeight = 0.4;

        // $views = $post['views'];
        $likes = $post['likeCount'];
        $comments = $post['commentCount'];

        $popularityScore = ($likes * $likesWeight) + ($comments * $commentsWeight);

        return $popularityScore;
    }
}