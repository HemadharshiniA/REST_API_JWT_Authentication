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
     public function storeRefreshToken($userId, $token, $expiry)
      {
         $sql = "UPDATE users
                 SET refresh_token = ?, refresh_token_expiry = ?
                 WHERE id = ?";

         $stmt = $this->conn->prepare($sql);

         $stmt->bind_param("ssi", $token, $expiry, $userId);

         return $stmt->execute();
      }

     public function findByRefreshToken($token)
      {
          $sql = "SELECT * FROM users
                  WHERE refresh_token = ?
                  AND refresh_token_expiry > NOW()";

          $stmt = $this->conn->prepare($sql);

          $stmt->bind_param("s", $token);

          $stmt->execute();

          $result = $stmt->get_result();

          return $result->fetch_assoc();
      }

     public function removeRefreshToken($userId)
      {
            $sql = "UPDATE users
                   SET refresh_token = NULL,
                        refresh_token_expiry = NULL
                   WHERE id = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("i", $userId);

            return $stmt->execute();
      }
}

?>