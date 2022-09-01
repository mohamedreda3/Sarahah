<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class ResetPassword
{
    private $conn;
    private $res;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->res = new Response;
    }
    public function sendEmail($data, ?string $type = '')
    {

        if (!isset($data['email']) || $data['email'] == null || !filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
            echo $this->res->getResponse(0, 'Invalid Email', null);
        } else {
            $email = ($data['email']);
            $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$email'");
            if (mysqli_num_rows($checkEmailQuery) > 0) {
                $uData = $this->getUserData($data);
                $randomId = $uData['randomId'];
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mmoh33650@gmail.com';
                $mail->Password   = 'pdtldkhyujgosjuc';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->addAddress($uData['to']);
                $mail->isHTML(true);
                $mail->Subject = $uData['subject'];
                $mail->Body = $uData['body'];
                $insertQuery = mysqli_query($this->conn, "UPDATE users SET resetcode = '$randomId' WHERE email = '$email'");
                if ($mail->send()) {
                    if ($insertQuery) {
                        $logOut = new Logout;
                        $logOut->destroy_all();
                        echo $this->res->getResponse(1, 'Success');
                    } else {
                        echo $this->res->getResponse(0, mysqli_error($this->conn));
                    }
                } else {
                    echo $this->res->getResponse(0, 'Failed');
                }
            } else {
                echo $this->res->getResponse(0, 'Email does not exist, you can create account if you do not have one', null);
            }
        }
    }

    public function getUserData($data)
    {
        $time = time();
        $randomId = substr(md5(uniqid($data['email'] . $time, true)), 0, 6);
        return array(
            'to' => $data['email'],
            'subject' => 'Reset password',
            'body' => "<p>Your Code is : $randomId</p>",
            'password' => '#M01212745939R*',
            'randomId' => $randomId
        );
    }
}


// margin: 30px auto; text-decoration: none; color: white !important; background: #db4979; display: block; width: 150px; min-height: 22px; padding: 23px; cursor: pointer; font-size: 18px; text-align: center; border-radius: 7px;box-shadow: 3px 6.5px 29px -2.5px grey;