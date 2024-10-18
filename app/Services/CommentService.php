<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Exception;

use function PHPUnit\Framework\isNull;

class CommentService
{
    /**
     * service to create comment.
     * @param array $data
     * @return array 

     */
    public function createComment(array $data)
    {
        try {
            $comment = Comment::create($data);
            return ['message' => 'comment created successfully', 'comment' => $comment, 'status' => 200];
        } catch (Exception $e) {
            return ['message' => 'create comment failed', 'error' => $e, 'status' => 404];
        }
    }
    /**
     * service to update comment data in storage.
     * @param Comment $comment
     * @param array $data
     * @return array 

     */
    public function updateComment(Comment $comment, array $data)
    {
        echo $comment->id;
        try {
            $comment = Comment::findOrFail($comment->id);
            $comment->update($data);
            return ['message' => 'comment updated successfully', 'comment' => $comment, 'status' => 200];
        } catch (Exception $e) {
            return ['message' => 'Update comment failed', 'error' => $e, 'status' => 404];
        }
    }
    /**
     * service method used to delete comment from storage.
     * @param Comment $comment
     *@return array

     */
    public function deleteComment(Comment $comment)
    {
        try {
            $comment = Comment::findOrFail($comment->id);
            $comment->delete();
            return ['message' => 'Comment deleted successfully', 'status' => 200];
        } catch (Exception $e) {
            return ['message' => 'Delete comment failed', 'error' => $e, 'status' => 404];
        }
    }
}
