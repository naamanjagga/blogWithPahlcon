<?php

use Phalcon\Mvc\Model;

class Blogs extends Model
{
    public $blog_id;
    public $user_id;
    public $title;
    public $file;
    public $content;
}