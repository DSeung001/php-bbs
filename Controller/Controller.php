<?php
namespace Controller;

class Controller{
    public function redirect($path, $message)
    {
        echo "<script>
                alert('$message');
                location.href='$path';
              </script>";
        exit();
    }

    public function redirectBack($message)
    {
        echo "<script>
                alert('$message');
                history.back();
              </script>";
        exit();
    }

    public function echoJson($data){
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}