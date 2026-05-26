<?php

require_once __DIR__ . '/../core/Database.php';

class Patient
{

    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function getAll($request)
    {
        $userId = $request['user']['user_id'];
        $sql = "SELECT * FROM patients WHERE user_id = ? ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        $result = $stmt->get_result();

        $patients = [];

        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }

        return $patients;
    }
    public function getById($id,$request)
    {
        $userId = $request['user']['user_id'];
        $sql = "SELECT * FROM patients WHERE id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii",$id,$userId);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($name, $age, $gender, $phone, $address,$request)
    {
        $userId = $request['user']['user_id'];

        $sql = "INSERT INTO patients(name,age,gender,phone,address,user_id)
                VALUES(?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param
        (
            "sisssi",
            $name,
            $age,
            $gender,
            $phone,
            $address, $userId
        );

        return $stmt->execute();
    }

    public function update($id, $name, $age, $gender, $phone, $address,$request)
    {
        $userId = $request['user']['user_id'];
        $sql = "UPDATE patients
                SET name=?, age=?, gender=?, phone=?, address=?
                WHERE id=? AND user_id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param
        (
            "sisssii",
            $name,
            $age,
            $gender,
            $phone,
            $address,
            $id, $userId
        );

        return $stmt->execute();
    }

    public function delete($id,$request)
    {
        $userId = $request['user']['user_id'];
        $sql = "DELETE FROM patients WHERE id=? AND user_id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ii", $id,$userId);

        return $stmt->execute();
    }
}