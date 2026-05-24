<?php

require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../helpers/Response.php';

class Patient_Ctrl
{

    public function index()
    {

        $patientModel = new Patient();

        $patients = $patientModel->getAll();

        Response::json(true, 'Patient List', $patients);
    }
    public function show($id)
    {
        $patientModel = new Patient();

        $patients = $patientModel->getById($id);
        
        if(!$patients)
            {
                Response::json(false,'Patient Not Found..!!',[],404);
            }

        Response::json(true, 'Patient Details', $patients);
    }

    public function create($request)
    {

        $body = $request['body'];

        $name = $body['name'] ?? '';
        $age = $body['age'] ?? '';
        $gender = $body['gender'] ?? '';
        $phone = $body['phone'] ?? '';
        $address = $body['address'] ?? '';

        if (!$name || !$age || !$gender || !$phone) {
            Response::json(false, 'Required fields missing..!!', [], 400);
        }

        $patientModel = new Patient();

        $patientModel->create(
            $name,
            $age,
            $gender,
            $phone,
            $address
        );

        Response::json(true, 'Patient Created');
    }

    public function update($request, $id)
    {

        $body = $request['body'];

        $patientModel = new Patient();

        $patientModel->update(
            $id,
            $body['name'],
            $body['age'],
            $body['gender'],
            $body['phone'],
            $body['address']
        );

        Response::json(true, 'Patient Updated');
    }

    public function delete($id)
    {

        $patientModel = new Patient();

        $patientModel->delete($id);

        Response::json(true, 'Patient Deleted');
    }
}

?>