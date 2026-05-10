<?php
// ================================================
// Produit.class.php - Style TP3
// Attributs public, connexion dans chaque méthode
// ================================================

class Produit
{
    /* attributs de la classe */
    public $id_produit;
    public $nom;
    public $description;
    public $prix;
    public $stock;
    public $image;
    public $id_categorie;

    function insertProduit()
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "INSERT INTO produits (nom, description, prix, stock, id_categorie, image)
                VALUES ('$this->nom','$this->description','$this->prix','$this->stock','$this->id_categorie','$this->image')";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }

    function listeProduits()
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT p.*, c.nom AS categorie
                FROM produits p
                LEFT JOIN categories c ON p.id_categorie = c.id_categorie
                ORDER BY p.id_produit DESC";
        $res = $pdo->query($req) or print_r($pdo->errorInfo());
        return $res;
    }

    function getProduit($id)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT p.*, c.nom AS categorie
                FROM produits p
                LEFT JOIN categories c ON p.id_categorie = c.id_categorie
                WHERE p.id_produit = $id";
        $res = $pdo->query($req) or print_r($pdo->errorInfo());
        return $res;
    }

    function rechercher($mot_cle)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $terme = "%$mot_cle%";
        $req = $pdo->prepare("SELECT p.*, c.nom AS categorie
                FROM produits p
                LEFT JOIN categories c ON p.id_categorie = c.id_categorie
                WHERE p.nom LIKE ? OR p.description LIKE ?
                ORDER BY p.nom ASC");
        $req->execute([$terme, $terme]);
        return $req;
    }

    function getParCategorie($id_categorie)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT p.*, c.nom AS categorie
                FROM produits p
                LEFT JOIN categories c ON p.id_categorie = c.id_categorie
                WHERE p.id_categorie = $id_categorie
                ORDER BY p.nom ASC";
        $res = $pdo->query($req) or print_r($pdo->errorInfo());
        return $res;
    }

    function modifierProduit($id)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE produits SET nom='$this->nom', description='$this->description',
                prix='$this->prix', stock='$this->stock', id_categorie='$this->id_categorie',
                image='$this->image' WHERE id_produit=$id";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }

    function modifierProduitSansImage($id)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE produits SET nom='$this->nom', description='$this->description',
                prix='$this->prix', stock='$this->stock', id_categorie='$this->id_categorie'
                WHERE id_produit=$id";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }

    function supprimerProduit($id)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "DELETE FROM produits WHERE id_produit='$id'";
        $pdo->exec($req);
    }

    function compterProduits()
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $res = $pdo->query("SELECT COUNT(*) AS total FROM produits");
        return $res->fetch(PDO::FETCH_ASSOC)['total'];
    }

    function compterRuptures()
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $res = $pdo->query("SELECT COUNT(*) AS total FROM produits WHERE stock = 0");
        return $res->fetch(PDO::FETCH_ASSOC)['total'];
    }

    function listeCategories()
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $res = $pdo->query("SELECT * FROM categories ORDER BY nom");
        return $res;
    }
}
?>
