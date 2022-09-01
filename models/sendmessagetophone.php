<?php
use MessageBird\Objects\Message;
use MessageBird\Client;
require_once './vendor/autoload.php';
class SendSMS
{
    private $conn;
    private $classResponse;
    public function __construct(Connectiontodb $db)
    {
        $this->conn = $db->connect();
        $this->classResponse = new Response;
    }

    public function sendsms($id, $phone)
    {
        if ($id == null) {
            echo $this->classResponse->getResponse(0, 'You Should Enter Message', null);
        } else {
            $MessageBird = new Client('a4M6fesaqMANBNQyY9zu5lrrW');
            $Message = new Message();
            $Message->originator = 'Mohamed Reda';
            $Message->recipients = array('+201212745939');
            $Message->body = 'This is a test message';

            $MessageBird->messages->create($Message);
            return true;
        }
    }

    public function confirmSigupMessage(?array $data)
    {

        if ($data['email'] == null || !filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
            echo $this->classResponse->getResponse(0, 'Enter validate Email', null);
        } else if ($data['message'] == null || !filter_var(trim(($data['message'])))) {
            echo $this->classResponse->getResponse(0, 'Enter Message', null);
        } else {
            $uemail = $data['email'];
            $message = $data['message'];
            $checkEmailExists = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$uemail'");
        }
    }
}
