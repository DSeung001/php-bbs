<?php
namespace DB;
require_once "../bootstrap.php";
use PDO;

class Connection
{
    private $root;
    private $conn = null;
    private $config;

    public function __construct()
    {
        $this->root = $_SERVER['DOCUMENT_ROOT'];
        $this->config = parse_ini_file($this->root . '/bbs/config.ini');
    }

    public function getConnection()
    {
        if ($this->conn == null) {
            try {
                // 기본 연결 정보로 접속하여 존재 여부 확인
                $dsn = "mysql:host={$this->config['DB_HOSTNAME']};charset=utf8";
                $conn = new PDO($dsn, $this->config['DB_USER'], $this->config['DB_PASSWORD']);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // 데이터베이스가 이미 존재하는지 확인하는 쿼리
                $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->config['DB_NAME']}'");

                if ($result->rowCount() == 0) {
                    // 데이터베이스가 존재하지 않으면 생성
                    $conn->exec("CREATE DATABASE {$this->config['DB_NAME']}");
                    echo "Database {$this->config['DB_NAME']} created successfully.<br/>";
                }

                $conn->query("use " . $this->config['DB_NAME']);

            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            $this->conn = $conn;
        }
        return $this->conn;
    }
}