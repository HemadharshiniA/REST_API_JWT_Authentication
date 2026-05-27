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
            
            $accessToken = JWT::generate
            ([
                    'user_id' => $user['id'],
                    'email' => $user['email']
            ]);

            $refreshToken = bin2hex(random_bytes(40));

            $expiryDate = date
            (
                'Y-m-d H:i:s', strtotime("+" . $_ENV['REFRESH_TOKEN_EXPIRY_DAYS'] . " days")
            );

            $userModel->storeRefreshToken
            (
                $user['id'],$refreshToken, $expiryDate
            );

            setcookie
            (
                     'refresh_token', $refreshToken,
                        [
                            'expires' => time() + ($_ENV['REFRESH_TOKEN_EXPIRY_DAYS'] * 24 * 60 * 60),
                            'path' => '/',
                            'httponly' => true,
                            'samesite' => 'Strict'
                        ]
            );

            Response::json(true,'Login Successful..!!',
                        [
                            'access_token' => $accessToken,
                            'expires_in' => $_ENV['ACCESS_TOKEN_EXPIRY']
                        ]
            );
    }
    //Refresh Method

    public function refresh()
    {
        if (!isset($_COOKIE['refresh_token']))
        {
            Response::json(false,'Refresh token missing', [], 401);
        }

        $refreshToken = $_COOKIE['refresh_token'];

        $userModel = new User();

        $user = $userModel->findByRefreshToken($refreshToken);

        if (!$user)
        {
            Response::json(false, 'Invalid or Expired Refresh Token',[],401);
        }

        $newAccessToken = JWT::generate
        ([
            'user_id' => $user['id'],
            'email' => $user['email']
        ]);

        // Sliding expiry

        $newExpiry = date('Y-m-d H:i:s', strtotime("+" . $_ENV['REFRESH_TOKEN_EXPIRY_DAYS'] . " days"));

        $userModel->storeRefreshToken($user['id'], $refreshToken, $newExpiry);

        setcookie('refresh_token',$refreshToken,
            [
                'expires' => time() + ($_ENV['REFRESH_TOKEN_EXPIRY_DAYS'] * 24 * 60 * 60),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );

        Response::json(true,'New Access Token Generated...!!',
            [
                'access_token' => $newAccessToken,
                'expires_in' => $_ENV['ACCESS_TOKEN_EXPIRY']
            ]
        );
    }

    // Logout 
    
    public function logout()
    {
        if (!isset($_COOKIE['refresh_token']))
        {
            Response::json(false, 'Already Logged Out....');
        }

        $refreshToken = $_COOKIE['refresh_token'];

        $userModel = new User();

        $user = $userModel->findByRefreshToken($refreshToken);

        if ($user)
        {
            $userModel->removeRefreshToken($user['id']);
        }

        setcookie(
            'refresh_token','',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );

        Response::json(true,'Logout Successful....!!');
    }
}

?>