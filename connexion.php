<?php
// ================================================
// connexion.php - Classe connexion style TP3
// ================================================
class connexion
{
    public function CNXbase()
    {
        $dbc = new PDO('mysql:host=localhost;dbname=store_php;charset=utf8', 'root', '');
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbc;
    }
}
?>
