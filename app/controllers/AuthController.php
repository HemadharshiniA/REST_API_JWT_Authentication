<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/JWT.php';

class Auth_Ctrl
{
    //Sign Up
    public function register($request)
    {
        $body = $request['body'];
        $name = $body['name'] ?? '';
        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';

        if(!$name || !$email || !$password)
            {
                Response::json(false,'All fields are required..!!',[],400);
            }
        $userModel = new User();
        $existingUser = $userModel->findByEmail($email);

        if($existingUser)
            {
                Response::json(false,'Email already exists..!!',[],400);
            }
            
            //password hash
            
            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

            $userModel->create($name,$email,$hashedPassword);

            Response::json(true,'Registration/Sign Up Successful..!!');
    }

    //Sign in
    public function login($request)
    {
        $body = $request['body'];
        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';

        if(!$email || !$password)
            {
                Response::json(false,'Email and Password required..!!',[],400);
            }

            $userModel = new User();

            $user = $userModel->findByEmail($email);

            if(!$user)
                {
                    Response::json(false,'Invalid Email..!!',[],401);
                }
            if(!password_verify($password,$user['password']))
                {
                    Response::json(false,'Invalid Password..!!',[],401);
                }
            
                $token = JWT::generate
                ([
                    'user_id' => $user['id'],
                    'email' => $user['email']
                ]);

                Response::json(true,'Login Successful..!!',
                [
                    'token' => $token,
                    'expires_in' => $_ENV['JWT_EXPIRY']
                ]);
    }
}

?>