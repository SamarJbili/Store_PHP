<?php
require_once 'connexion.php';

class Panier {

    private $pdo;

    public function __construct() {
        $cnx = new connexion();
        $this->pdo = $cnx->CNXbase();
    }

    /* -------------------------------------------------------
       Charger le panier depuis la BDD → dans $_SESSION['panier']
    ------------------------------------------------------- */
    public function chargerDepuisBDD($id_utilisateur) {
        $stmt = $this->pdo->prepare("
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

    /* -------------------------------------------------------
       Ajouter ou mettre à jour un article
    ------------------------------------------------------- */
    public function ajouterOuMettreAJour($id_utilisateur, $id_produit, $quantite) {
        // INSERT si n'existe pas, UPDATE quantite si existe déjà
        $stmt = $this->pdo->prepare("
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

    /* -------------------------------------------------------
       Modifier la quantité exacte d'un article
    ------------------------------------------------------- */
    public function modifierQuantite($id_utilisateur, $id_produit, $quantite) {
        if (intval($quantite) <= 0) {
            $this->supprimer($id_utilisateur, $id_produit);
            return;
        }
        $stmt = $this->pdo->prepare("
            UPDATE panier SET quantite = :quantite
            WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit
        ");
        $stmt->execute([
            ':quantite'       => intval($quantite),
            ':id_utilisateur' => intval($id_utilisateur),
            ':id_produit'     => intval($id_produit),
        ]);
    }

    /* -------------------------------------------------------
       Supprimer un article
    ------------------------------------------------------- */
    public function supprimer($id_utilisateur, $id_produit) {
        $stmt = $this->pdo->prepare("
            DELETE FROM panier
            WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit
        ");
        $stmt->execute([
            ':id_utilisateur' => intval($id_utilisateur),
            ':id_produit'     => intval($id_produit),
        ]);
    }

    /* -------------------------------------------------------
       Vider tout le panier
    ------------------------------------------------------- */
    public function vider($id_utilisateur) {
        $stmt = $this->pdo->prepare("
            DELETE FROM panier WHERE id_utilisateur = :id_utilisateur
        ");
        $stmt->execute([':id_utilisateur' => intval($id_utilisateur)]);
    }
}
?>