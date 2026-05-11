<?php


class Utilisateur
{
    
    public $id_utilisateur;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $telephone;
    public $adresse;
    public $role;

    function insertUtilisateur()
    {
        require_once 'connexion.php';
        $cnx  = new connexion();
        $pdo  = $cnx->CNXbase();
        $hash = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $req  = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, adresse, role)
                 VALUES ('$this->nom','$this->prenom','$this->email','$hash','$this->telephone','$this->adresse','client')";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }

    function getUtilisateur($id)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT * FROM utilisateurs WHERE id_utilisateur=$id";
        $res = $pdo->query($req) or print_r($pdo->errorInfo());
        return $res;
    }

    function getParEmail($email)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT * FROM utilisateurs WHERE email='$email'";
        $res = $pdo->query($req) or print_r($pdo->errorInfo());
        return $res;
    }

    function modifierUtilisateur($id)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE utilisateurs SET nom='$this->nom', prenom='$this->prenom',
                email='$this->email', telephone='$this->telephone', adresse='$this->adresse'
                WHERE id_utilisateur=$id";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }

    function changerMotDePasse($id)
    {
        require_once 'connexion.php';
        $cnx  = new connexion();
        $pdo  = $cnx->CNXbase();
        $hash = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $req  = "UPDATE utilisateurs SET mot_de_passe='$hash' WHERE id_utilisateur=$id";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }

    function emailExiste($email)
    {
        require_once 'connexion.php';
        $cnx  = new connexion();
        $pdo  = $cnx->CNXbase();
        $res  = $pdo->query("SELECT id_utilisateur FROM utilisateurs WHERE email='$email'");
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        return count($data) > 0;
    }

    function verifierConnexion($email, $mot_de_passe)
    {
        $res  = $this->getParEmail($email);
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($data && password_verify($mot_de_passe, $data[0]['mot_de_passe'])) {
            return $data[0];
        }
        return false;
    }
}
?>
