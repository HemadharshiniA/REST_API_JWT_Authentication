<?php

require_once __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../app/helpers/Response.php';
require_once __DIR__ . '/../app/helpers/JWT.php';

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Router.php';

require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Patient.php';

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/PatientController.php';

require_once __DIR__ . '/../app/middleware/JsonMiddleware.php';
require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';

//middleware

$request = JsonMiddleware::handle();

//router

$router = new Router();

//controller --- obj creation

$authController = new Auth_Ctrl();
$patientController = new Patient_Ctrl();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);  //Extracts only PATH from URL

$url = str_replace('/REST_API_JWT_Authentication', '', $url);  //Removes project folder name from URL

$method = $_SERVER['REQUEST_METHOD'];

// PUBLIC ROUTES

$router->add('POST', '/app/register', function() use ($authController, $request) {
    $authController->register($request);
});

$router->add('POST', '/app/login', function() use ($authController, $request) {
    $authController->login($request);
});

// PROTECTED ROUTES

$router->add('GET', '/app/patients', function() use ($patientController) {

    $user = AuthMiddleware::handle();

    $request['user'] = $user;

    $patientController->index($request);
});

$router->add('GET', '/app/patients/{id}', function($id) use ($patientController) {

    $user = AuthMiddleware::handle();

    $request['user'] = $user;

    $patientController->show($id,$request);
});

$router->add('POST', '/app/patients', function() use ($patientController, $request) {

    $user = AuthMiddleware::handle();

    $request['user'] = $user;

    $patientController->create($request);
});

$router->add('PUT', '/app/patients/{id}', function($id) use ($patientController, $request) {

    $user = AuthMiddleware::handle();

    $request['user'] = $user;

    $patientController->update($id,$request);
});

$router->add('DELETE', '/app/patients/{id}', function($id) use ($patientController) {

    $user = AuthMiddleware::handle();

    $request['user'] = $user;

    $patientController->delete($id,$reques);
});

$router->dispatch($method, $url);

?>