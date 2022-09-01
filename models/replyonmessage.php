<?php
class ReplyOnMessage
{
    private $conn;
    private $classResponse;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->classResponse = new Response;
    }

    public function sendReply(?array $data, ?string $token = '')
    {
        if (!isset($data['messageid']) || $data['messageid'] == '') {
            echo $this->classResponse->getResponse(0, 'Invalid Message Id');
        } else if (!isset($data['reply']) || $data['reply'] == null) {
            echo $this->classResponse->getResponse(0, 'Enter Reply', null);
        } else {
            $messageid = $data['messageid'];
            $reply = $data['reply'];
            $unkown = true;
            $checkEmailExists = mysqli_query($this->conn, "SELECT * FROM messages WHERE id = '$messageid'");
            if ((mysqli_num_rows($checkEmailExists) > 0)) {
                $insertQuery = mysqli_query($this->conn, "INSERT INTO `replies` (`message_id`, `reply`, `sent_at`, `unknown`) VALUES ('$messageid', '$reply', NOW(), '$unkown')");
                if ($insertQuery) {
                    echo $this->classResponse->getResponse(1, 'success');
                } else {
                    echo $this->classResponse->getResponse(0, mysqli_error($this->conn), null);
                }
            } else {
                echo $this->classResponse->getResponse(0, 'Message not found', null);
            }
        }
    }
}
