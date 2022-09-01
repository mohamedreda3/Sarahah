<?php

class Controller
{
    private $conn;
    private $response;
    private $classResponse;
    function __construct(Connectiontodb $db)
    {
        $this->conn = $db;
        $this->classResponse = new Response();
    }

    function processRequest($request, $data = [], $token = '')
    {
        if ($request == 'signup') {
            $signup = new Signup($this->conn);
            $signup->sendData($data);
        } else if ($request == 'confirmemail') {
            $sendsms = new SendSMS($this->conn);
            $sendsms->confirmSigupMessage($data);
        } else if ($request == 'login') {
            $login = new LogIn($this->conn);
            $login->sendData($data);
        } else if ($request == 'resetpassword') {
            $reset = new ResetPassword($this->conn);
            $reset->sendEmail($data);
        } else if ($request == 'confirmcode') {
            $reset = new ConfirmCode($this->conn);
            $reset->confirm($data);
        } else if ($request == 'getallinformation') {
            $createcategory = new GetAllInformation($this->conn);
            $createcategory->exportInformation($token);
        } else if ($request == 'getmessages') {
            $createcategory = new GetAllMessages($this->conn);
            $createcategory->exportInformation($token);
        } else if ($request == 'sendmessage') {
            $sendMessage = new SendMessage($this->conn);
            $sendMessage->sendMessage($data, $token);
        } else if ($request == 'changepassword') {
            $createcategory = new Changepassword($this->conn);
            $createcategory->sendData($data);
        } else if ($request == 'updatedata') {
            $createcategory = new UpdateData($this->conn);
            $createcategory->update($data, $token);
        } else if ($request == 'updatemessage') {
            $createcategory = new UpdateMessage($this->conn);
            $createcategory->update($data, $token);
        } else if ($request == 'deletemessage') {
            $createcategory = new DeleteMessage($this->conn);
            $createcategory->update($data, $token);
        }
    }
    // changepassword
}
