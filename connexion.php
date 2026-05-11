<?php
class connexion
{
    public function CNXbase()
    {
        $dbc = new PDO('mysql:host=localhost;dbname=store_php;charset=utf8', 'root', '');
        return $dbc;
    }
}
?>