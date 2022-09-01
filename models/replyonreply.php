<?php
class ReplyOnReply
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
        if (!isset($data['replyid']) || $data['replyid'] == '') {
            echo $this->classResponse->getResponse(0, 'Invalid Reply Id');
        } else if (!isset($data['reply']) || $data['reply'] == null) {
            echo $this->classResponse->getResponse(0, 'Enter Reply', null);
        } else {
            $messageid = $data['replyid'];
            $reply = $data['reply'];
            $unkown = true;
            $checkEmailExists = mysqli_query($this->conn, "SELECT * FROM replies WHERE id = '$messageid'");
            if ((mysqli_num_rows($checkEmailExists) > 0)) {
                $insertQuery = mysqli_query($this->conn, "INSERT INTO `repliesonreplies` (`reply_id`, `reply`, `sent_at`, `unknown`) VALUES ('$messageid', '$reply', NOW(), '$unkown')");
                if ($insertQuery) {
                    echo $this->classResponse->getResponse(1, 'success');
                } else {
                    echo $this->classResponse->getResponse(0, mysqli_error($this->conn), null);
                }
            } else {
                echo $this->classResponse->getResponse(0, 'Reply not found', null);
            }
        }
    }
}
