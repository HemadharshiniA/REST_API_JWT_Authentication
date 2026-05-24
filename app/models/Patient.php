<?php

require_once __DIR__ . '/../core/Database.php';

class Patient
{

    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function getAll()
    {

        $sql = "SELECT * FROM patients ORDER BY id DESC";

        $result = $this->conn->query($sql);

        $patients = [];

        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }

        return $patients;
    }
    public function getById($id)
    {

        $sql = "SELECT * FROM patients WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($name, $age, $gender, $phone, $address)
    {

        $sql = "INSERT INTO patients(name,age,gender,phone,address)
                VALUES(?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param
        (
            "sisss",
            $name,
            $age,
            $gender,
            $phone,
            $address
        );

        return $stmt->execute();
    }

    public function update($id, $name, $age, $gender, $phone, $address)
    {

        $sql = "UPDATE patients
                SET name=?, age=?, gender=?, phone=?, address=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param
        (
            "sisssi",
            $name,
            $age,
            $gender,
            $phone,
            $address,
            $id
        );

        return $stmt->execute();
    }

    public function delete($id)
    {

        $sql = "DELETE FROM patients WHERE id=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}