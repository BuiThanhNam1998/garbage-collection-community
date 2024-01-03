<?php

namespace App\Services\Reactions;

use App\Models\GarbagePost;
use App\Models\PostShare;

class ReactionService 
{
    public function createReactionForPost(GarbagePost $post, $data) {
        return $post->reactions()->create($data);
    }

    public function createReactionForPostShare(PostShare $postShare, $data) {
        return $postShare->reactions()->create($data);
    }
}
