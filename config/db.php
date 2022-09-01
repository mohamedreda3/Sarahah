<?php
class Connectiontodb
{
    public function connect()
    {
        return mysqli_connect('localhost', 'root', '', 'sarahah');
    }
}
