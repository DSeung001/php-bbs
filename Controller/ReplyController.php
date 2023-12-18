<?php
namespace Controller;
require_once "../bootstrap.php";
use Model\Reply;

route();
function route(){
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $protocolHost = $protocol . '://' . $host;
    $ReplyController = new ReplyController();

    if ($_SERVER['HTTP_REFERER'] == $protocolHost . "/bbs/view/create.php"
        && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $ReplyController->create();
    } else {
        $ReplyController->redirectBack('잘못된 접근입니다.');
    }
}

class ReplyController extends BaseController
{
    private $reply;

    public function __construct()
    {
        $this->reply = new reply();
    }

    public function create(){

    }
}