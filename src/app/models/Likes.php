<?php

use Phalcon\Mvc\Model;

class Likes extends Model
{
    public $like_id;
    public $user_id;
    public $blog_id;
}