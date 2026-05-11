<?php

class Panier
{
    public $id_panier;
    public $id_utilisateur;
    public $id_produit;
    public $quantite;
    public $date_ajout;

    function chargerDepuisBDD($id_utilisateur)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT p.id_produit, pr.nom, pr.prix, pr.image, p.quantite
                FROM panier p
                JOIN produits pr ON pr.id_produit = p.id_produit
                WHERE p.id_utilisateur = $id_utilisateur";
        $res = $pdo->query($req);
        $lignes = $res->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['panier'] = [];
        foreach ($lignes as $ligne) {
            $id = intval($ligne['id_produit']);
            $_SESSION['panier'][$id] = [
                'id_produit' => $id,
                'nom'        => $ligne['nom'],
                'prix'       => $ligne['prix'],
                'image'      => $ligne['image'],
                'quantite'   => intval($ligne['quantite']),
            ];
        }
    }

    function ajouterOuMettreAJour($id_utilisateur, $id_produit, $quantite)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $res = $pdo->query("SELECT quantite FROM panier WHERE id_utilisateur=$id_utilisateur AND id_produit=$id_produit");
        $data = $res->fetchAll(PDO::FETCH_ASSOC);

        if ($data) {
            $nouvelle_qty = $data[0]['quantite'] + $quantite;
            $pdo->exec("UPDATE panier SET quantite=$nouvelle_qty, date_ajout=NOW()
                        WHERE id_utilisateur=$id_utilisateur AND id_produit=$id_produit");
        } else {
            $pdo->exec("INSERT INTO panier (id_utilisateur, id_produit, quantite, date_ajout)
                        VALUES ($id_utilisateur, $id_produit, $quantite, NOW())");
        }
    }

    function modifierQuantite($id_utilisateur, $id_produit, $quantite)
    {
        if (intval($quantite) <= 0) {
            $this->supprimer($id_utilisateur, $id_produit);
            return;
        }
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $pdo->exec("UPDATE panier SET quantite=$quantite
                    WHERE id_utilisateur=$id_utilisateur AND id_produit=$id_produit");
    }

    function supprimer($id_utilisateur, $id_produit)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $pdo->exec("DELETE FROM panier WHERE id_utilisateur=$id_utilisateur AND id_produit=$id_produit");
    }

    function vider($id_utilisateur)
    {
        require_once 'connexion.php';
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $pdo->exec("DELETE FROM panier WHERE id_utilisateur=$id_utilisateur");
    }
}
?>