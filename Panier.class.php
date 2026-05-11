<?php
require_once 'connexion.php';

class Panier {

    public $id_panier;
    public $id_utilisateur;
    public $id_produit;
    public $quantite;
    public $date_ajout;

    public function chargerDepuisBDD($id_utilisateur) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            SELECT p.id_produit, pr.nom, pr.prix, pr.image, p.quantite
            FROM panier p
            JOIN produits pr ON pr.id_produit = p.id_produit
            WHERE p.id_utilisateur = :id_utilisateur
        ");
        $stmt->execute([':id_utilisateur' => intval($id_utilisateur)]);
        $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    public function ajouterOuMettreAJour($id_utilisateur, $id_produit, $quantite) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            INSERT INTO panier (id_utilisateur, id_produit, quantite, date_ajout)
            VALUES (:id_utilisateur, :id_produit, :quantite, NOW())
            ON DUPLICATE KEY UPDATE
                quantite   = quantite + VALUES(quantite),
                date_ajout = NOW()
        ");
        $stmt->execute([
            ':id_utilisateur' => intval($id_utilisateur),
            ':id_produit'     => intval($id_produit),
            ':quantite'       => intval($quantite),
        ]);
    }

    public function modifierQuantite($id_utilisateur, $id_produit, $quantite) {
        if (intval($quantite) <= 0) {
            $this->supprimer($id_utilisateur, $id_produit);
            return;
        }
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            UPDATE panier SET quantite = :quantite
            WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit
        ");
        $stmt->execute([
            ':quantite'       => intval($quantite),
            ':id_utilisateur' => intval($id_utilisateur),
            ':id_produit'     => intval($id_produit),
        ]);
    }

    public function supprimer($id_utilisateur, $id_produit) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            DELETE FROM panier
            WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit
        ");
        $stmt->execute([
            ':id_utilisateur' => intval($id_utilisateur),
            ':id_produit'     => intval($id_produit),
        ]);
    }

    public function vider($id_utilisateur) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            DELETE FROM panier WHERE id_utilisateur = :id_utilisateur
        ");
        $stmt->execute([':id_utilisateur' => intval($id_utilisateur)]);
    }
}
?>