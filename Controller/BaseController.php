<?php
namespace Controller;

class BaseController{
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
}