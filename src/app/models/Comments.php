<?php

use Phalcon\Mvc\Model;

class Comments extends Model
{
    public $comment_id;
    public $user_id;
    public $blog_id;
    public $comment;
}