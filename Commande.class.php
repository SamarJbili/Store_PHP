<?php
require_once 'connexion.php';

class Commande {

    public $id_commande;
    public $id_utilisateur;
    public $date_commande;
    public $statut;
    public $adresse_livraison;
    public $total;

    public function creerCommande($id_utilisateur, $adresse, $total, $panier) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $id_utilisateur = intval($id_utilisateur);
        $adresse = addslashes($adresse);
        $total = floatval($total);
        $req = "INSERT INTO commandes (id_utilisateur, date_commande, statut, adresse_livraison, total)
                VALUES ($id_utilisateur, NOW(), 'en attente', '$adresse', $total)";
        $pdo->exec($req);
        $id_commande = intval($pdo->lastInsertId());

        foreach ($panier as $item) {
            $id_produit = intval($item['id_produit']);
            $quantite = intval($item['quantite']);
            $prix = floatval($item['prix']);
            $reqLigne = "INSERT INTO lignes_commande (id_commande, id_produit, quantite, prix_unitaire)
                         VALUES ($id_commande, $id_produit, $quantite, $prix)";
            $pdo->exec($reqLigne);
        }

        return $id_commande;
    }

    public function listeCommandes($statut = '', $search = '') {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $sql = "SELECT c.id_commande, c.date_commande, c.statut, c.total,
                       c.adresse_livraison, u.nom, u.email
                FROM commandes c
                JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur";

        $conditions = [];
        if ($statut != '') {
            $statut = addslashes($statut);
            $conditions[] = "c.statut = '$statut'";
        }
        if ($search != '') {
            $searchTxt = addslashes($search);
            $idSearch = intval($search);
            $conditions[] = "(u.nom LIKE '%$searchTxt%' OR u.email LIKE '%$searchTxt%' OR c.id_commande = $idSearch)";
        }
        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY c.date_commande DESC";

        $res = $pdo->query($sql);
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommande($id) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $id = intval($id);
        $req = "SELECT c.*, u.nom, u.email
                FROM commandes c
                JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur
                WHERE c.id_commande = $id";
        $res = $pdo->query($req);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getLignes($id_commande) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $id_commande = intval($id_commande);
        $req = "SELECT l.quantite, l.prix_unitaire, p.nom, p.image
                FROM lignes_commande l
                JOIN produits p ON p.id_produit = l.id_produit
                WHERE l.id_commande = $id_commande";
        $res = $pdo->query($req);
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changerStatut($id_commande, $statut) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $id_commande = intval($id_commande);
        $statut = addslashes($statut);
        $req = "UPDATE commandes SET statut = '$statut' WHERE id_commande = $id_commande";
        $pdo->exec($req);
    }

    public function statsParStatut() {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->query("
            SELECT statut, COUNT(*) as nb, SUM(total) as ca
            FROM commandes GROUP BY statut
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>