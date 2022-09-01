<?php
class ConfirmCode
{
    private $conn;
    private $res;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->res = new Response;
    }
    public function confirm($data)
    {
        if (!isset($data['resetcode']) || ($data['resetcode'] == null)) {
            echo $this->res->getResponse(0, 'Invalid Code', null);
        } else {
            $resetCode = $data['resetcode'];
            $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM users WHERE resetcode = '$resetCode'");
            if (mysqli_num_rows($checkEmailQuery) > 0) {
                $getEmail = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM users WHERE resetcode = '$resetCode'"))['email'];
                $getType = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM users WHERE resetcode = '$resetCode'"))['usertype'];
                echo $this->res->getResponse(1, 'Success', null, array(
                    'email' => $getEmail,
                    'type' => $getType
                ));
            } else {
                echo $this->res->getResponse(0, 'Wrond Code go to your email to confirm', null);
            }
        }
    }
}
