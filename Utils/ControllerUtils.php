<?php

namespace Utils;

trait ControllerUtils   {
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

    public function parametersCheck(...$parameters): bool
    {
        foreach ($parameters as $parameter){
            if (empty($parameter)){
                return false;
            }
        }
        return true;
    }
}