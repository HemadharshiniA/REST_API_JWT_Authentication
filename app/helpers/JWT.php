<?php

class JWT
{

    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function generate($payload)
    {

        $header = json_encode
        ([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]);

        $issuedAt = time();
        $expiry = $issuedAt + $_ENV['ACCESS_TOKEN_EXPIRY'];

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $expiry;

        $headerEncoded = self::base64UrlEncode($header);

        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac
        (
            'sha256',
            $headerEncoded . "." . $payloadEncoded,
            $_ENV['JWT_SECRET'],
            true
        );

        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }

    public static function validate($token)
    {

        $parts = explode('.', $token);

        if (count($parts) != 3)
        {
            return false;
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        //To compare whether token was modified
        $signature = hash_hmac
        (
            'sha256',
            $headerEncoded . "." . $payloadEncoded,
            $_ENV['JWT_SECRET'],
            true
        );

        $expectedSignature = self::base64UrlEncode($signature);

        if ($expectedSignature !== $signatureEncoded) 
        {
            return false;
        }

        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);

        if ($payload['exp'] < time()) 
        {
            return false;
        }

        return $payload;
    }
}

?>