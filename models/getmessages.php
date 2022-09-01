<?php
class GetAllMessages
{
    private $conn;
    private $res;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->res = new Response;
    }

    public function exportInformation(?string $token = '')
    {
        if ($token != '') {
            $email = '';
            if (!str_starts_with($token, 'ftre')) {
                $decodeToken = new GetToken;
                $payload = $decodeToken->decodeAccessToken($token);
                $expTime = $payload['exp'];
                $timeNow = time();
                $email = base64_decode($payload['data']);
            } else {
                $email = base64_decode(str_rot13(base64_decode(str_rot13($token))));
            }

            if (str_starts_with($token, 'ftre') || ($timeNow < $expTime)) {
                $checkEmailQuery = '';
                if (str_starts_with($token, 'ftre')) {
                    $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM messages WHERE useremail = '$email' AND is_public = '1'");
                } else {
                    $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM messages WHERE useremail = '$email'");
                }
                if (mysqli_num_rows($checkEmailQuery) > 0) {
                    $messages = [];
                    while ($dataAssocc = mysqli_fetch_assoc($checkEmailQuery)) {

                        $messId = $dataAssocc['id'];
                        $replies = [];

                        $getReply = mysqli_query($this->conn, "SELECT * FROM replies WHERE message_id = '$messId'");
                        while ($reply = mysqli_fetch_assoc($getReply)) {

                            $replyId = $reply['id'];
                            $getReplyOnReply = mysqli_query($this->conn, "SELECT * FROM repliesonreplies WHERE reply_id = '$replyId'");
                            $repliesOnReplies = [];
                            while ($replyOnReply = mysqli_fetch_assoc($getReplyOnReply)) {
                                array_push($repliesOnReplies, $replyOnReply);
                            }
                            array_push($replies, array("reply" => $reply, "repliesonreply" => $repliesOnReplies));
                        }

                        array_push($messages, array("messagecontent" => $dataAssocc, "replies" => $replies));
                    }
                    print_r(json_encode($messages));
                } else {
                    echo $this->res->getResponse(0, 'You have not any message', null);
                }
            } else {
                echo $this->res->getResponse(0, 'Expired Token');
            }
        } else {
            echo $this->res->getResponse(0, 'Invalid Token');
        }
    }
}
