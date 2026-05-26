<?php

require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../helpers/Response.php';

class Patient_Ctrl
{

    public function index($request)
    {

        $patientModel = new Patient();

        $patients = $patientModel->getAll($request);

        Response::json(true, 'Patient List', $patients);
    }
    public function show($id,$request)
    {
        $patientModel = new Patient();

        $patients = $patientModel->getById($id,$request);
        
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

        $created = $patientModel->create(
            $name,
            $age,
            $gender,
            $phone,
            $address,
            $request
        );

        if (!$created)
        {
            Response::json(false, 'Patient Creation Failed', [], 500);
        }

        Response::json(true, 'Patient Created');
    }

    public function update($id,$request)
    {

        $body = $request['body'];

        $name = $body['name'] ?? '';
        $age = $body['age'] ?? '';
        $gender = $body['gender'] ?? '';
        $phone = $body['phone'] ?? '';
        $address = $body['address'] ?? '';

        $patientModel = new Patient();

        $updated = $patientModel->update(
            $id,
            $name,
            $age,
            $gender,
            $phone,
            $address,
            $request
        );

        if (!$updated)
        {
            Response::json(false, 'Patient Update Failed or Unauthorized', [], 400);
        }

        Response::json(true, 'Patient Updated');
    }

    public function delete($id, $request)
    {
        $patientModel = new Patient();

        $deleted = $patientModel->delete($id, $request);

        if (!$deleted)
        {
            Response::json(false, 'Patient Delete Failed or Unauthorized', [], 400);
        }

        Response::json(true, 'Patient Deleted Successfully');
    }
}

?>