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
            $confirm = new ConfirmCode($this->conn);
            $confirm->confirm($data);
        } else if ($request == 'getallinformation') {
            $export = new GetAllInformation($this->conn);
            $export->exportInformation($token);
        } else if ($request == 'getmessages') {
            $exportInformation = new GetAllMessages($this->conn);
            $exportInformation->exportInformation($token);
        } else if ($request == 'sendmessage') {
            $sendMessage = new SendMessage($this->conn);
            $sendMessage->sendMessage($data, $token);
        } else if ($request == 'changepassword') {
            $sendData = new Changepassword($this->conn);
            $sendData->sendData($data);
        } else if ($request == 'updatedata') {
            $createcategory = new UpdateData($this->conn);
            $createcategory->update($data, $token);
        } else if ($request == 'updatemessage') {
            $update = new UpdateMessage($this->conn);
            $update->update($data, $token);
        } else if ($request == 'deletemessage') {
            $delete = new DeleteMessage($this->conn);
            $delete->update($data, $token);
        } else if ($request == 'replyonmessage') {
            $sendReply = new ReplyOnMessage($this->conn);
            $sendReply->sendReply($data, $token);
        } else if ($request == 'replyonreply') {
            $sendReply = new ReplyOnReply($this->conn);
            $sendReply->sendReply($data, $token);
        }
    }
}
