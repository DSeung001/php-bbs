<?php
namespace DB;
require_once "../bootstrap.php";
use Migration\Post;
use Migration\Reply;

new Migration();

class Migration
{
    public function __construct()
    {
        $this->post = new post();
        $this->reply = new reply();

        $this->post->migrate();
        $this->reply->migrate();
    }
}