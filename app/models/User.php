<?php

require_once __DIR__ . '/../core/Database.php';

class User
{
     private $conn;

     public function __construct()
     {
        $this->conn = Database::connect();
     }
     public function findByEmail($email)
     {
        $sql = "SELECT * FROM users WHERE email = ?";

        // bind parameter ---> to prevent sql injection

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s",$email);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
     }
     public function create($name,$email,$password)
     {
        $sql = "INSERT INTO users(name,email,password) VALUES(?,?,?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss",$name,$email,$password);

        return $stmt->execute();
     }
}

?>