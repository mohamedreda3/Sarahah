<?php

class Changepassword
{
    private $conn;
    private $res;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->res = new Response;
    }
    public function sendData(?array $data = [], ?string $type = '')
    {
        $operators = ['CHENGE_PASSWORD', 'TYPE_NEW_PASSOWRD'];
        // echo $data['operator'];
        if (isset($data['operator']) && in_array($data['operator'], $operators)) {
            $oldpassword = '';
            if (!isset($data['email']) || $data['email'] == null || !filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
                echo $this->res->getResponse(0, 'Enter validate Email', null);
            } else if ((!isset($data['oldpassword']) || ($data['oldpassword'] == null) || $data['newpassword'] == null) && (isset($data['operator']) && $data['operator'] == "CHENGE_PASSWORD")) {
                echo $this->res->getResponse(0, 'Enter old password', null);
            } else if ((!isset($data['newpassword']) || ($data['newpassword'] == null) || $data['newpassword'] == null) && (isset($data['operator']))) {
                echo $this->res->getResponse(0, 'Enter new password', null);
            } else if (!isset($data['operator']) || ($data['operator'] == null)) {
                echo $this->res->getResponse(0, 'Enter operator', null);
            } else if (!isset($data['type']) || ($data['type'] == null)) {
                echo $this->res->getResponse(0, 'Enter user type', null);
            } else {
                $uemail = trim($data['email']);
                $type = trim($data['type']);
                $checkDataQuery = '';
                if ($data['operator'] == "CHENGE_PASSWORD") {
                    $oldpassword = md5(trim($data['oldpassword']));
                    $checkDataQuery = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$uemail' AND pass = '$oldpassword'");
                }
                $newpassword = md5(trim($data['newpassword']));
                $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$uemail' AND  usertype = 'User'");
                if (mysqli_num_rows($checkEmailQuery) > 0) {
                    if (($data['operator'] == "CHENGE_PASSWORD")) {
                        if (mysqli_num_rows($checkDataQuery) > 0) {
                            $updatePasswordQuery = mysqli_query($this->conn, "UPDATE users SET pass = '$newpassword' WHERE email = '$uemail'");
                            if ($updatePasswordQuery) {
                                echo $this->res->getResponse(1, 'success');
                            } else {
                                echo $this->res->getResponse(0, mysqli_error($this->conn));
                            }
                        } else {
                            echo $this->res->getResponse(0, 'Incorrect Old Password');
                        }
                    } else {
                        $updatePasswordQuery = mysqli_query($this->conn, "UPDATE users SET pass = '$newpassword' WHERE email = '$uemail'");
                        if ($updatePasswordQuery) {
                            echo $this->res->getResponse(1, 'success');
                        } else {
                            echo $this->res->getResponse(0, mysqli_error($this->conn));
                        }
                    }
                } else {
                    echo $this->res->getResponse(0, 'Email does not exist, you can create account if you do not have one', null);
                }
            }
        } else {
            echo $this->res->getResponse(0, 'Invalid request');
        }
    }
}
