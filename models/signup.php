<?php

class Signup
{

    private $conn;
    private $res;
    private $classResponse;
    public function __construct(Connectiontodb $db)
    {
        $this->conn = $db->connect();
        $this->classResponse = new Response();
    }

    public function sendData(?array $data = [], $token = '')
    {
        $userTypes = ['USER', 'ADMIN'];
        $userSex = ['MALE', 'FEMALE'];
        if (!isset($data['email']) || $data['email'] == null || !filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
            echo $this->classResponse->getResponse(0, 'Enter validate Email', null);
        } else if (!isset($data['phone']) || $data['phone'] == null || !filter_var(trim(intval($data['phone'])), FILTER_VALIDATE_INT)) {
            echo $this->classResponse->getResponse(0, 'Enter validate Phone number', null);
        } else if (!isset($data['password']) || $data['password'] == null) {
            echo $this->classResponse->getResponse(0, 'Enter password', null);
        } else if (!isset($data['name']) || $data['name'] == null) {
            echo $this->classResponse->getResponse(0, 'Enter userName', null);
        } else if (!isset($data['type']) || empty($data['type']) || !in_array(strtoupper($data['type']), $userTypes)) {
            echo $this->classResponse->getResponse(0, 'Enter user type (User)', null);
        } else if (!isset($data['sex']) || empty($data['sex']) || !in_array(strtoupper($data['sex']), $userSex)) {
            echo $this->classResponse->getResponse(0, 'Enter user Sex (Male, Female)', null);
        }  else {
            $userType = trim($data['type']);
            $uemail = trim($data['email']);
            $password = md5(trim($data['password']));
            $phonenumber = strval(trim($data['phone']));
            $uname = trim($data['name']);
            $sex = trim($data['sex']);
            $aboutU = '';
            if (isset($data['about_u'])) {
                $aboutU = $data['about_u'];
                if(strlen($aboutU) > 200){
                    $aboutU = substr($aboutU, 0, 200);
                }
            }
            $loc = $this->getLocation();
            $checkEmailExists = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$uemail'");
            if (!(mysqli_num_rows($checkEmailExists) > 0)) {
                $unique = uniqid("ec");
                $id = substr(strrev($unique), 0, 6);
                $insertQuery = mysqli_query($this->conn, "INSERT INTO users(email, pass, username, phone, created_at, usertype, confirmcode, loc, about_u, sex) VALUES('$uemail', '$password','$uname','$phonenumber', NOW(), '$userType', '$id', '$loc', '$aboutU', '$sex')");
                if ($insertQuery) {
                    $jwtKey = new GetToken;
                    $jwtKey->processPayload($uemail);
                    echo $this->classResponse->getResponse(1, 'success', $jwtKey->exportAccessToken());
                } else {
                    echo $this->classResponse->getResponse(0, mysqli_error($this->conn), null);
                }
            } else {
                echo $this->classResponse->getResponse(0, 'Email does not exist', null);
            }
        }
    }


    public function getLocation()
    {
        $PublicIP = json_decode(file_get_contents('https://ipinfo.io/json?token=19b65ad9d8f6c6'), true)['ip'];
        $json     = file_get_contents("http://ipinfo.io/$PublicIP?token=19b65ad9d8f6c6");
        $json     = json_decode($json, true);
        $country  = $json['country'];
        $region   = $json['region'];
        $city     = $json['city'];
        return $country . " - " . $region . " - " . $city;
    }
}
