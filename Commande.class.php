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
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("
                INSERT INTO commandes (id_utilisateur, date_commande, statut, adresse_livraison, total)
                VALUES (:id_utilisateur, NOW(), 'en attente', :adresse, :total)
            ");
            $stmt->execute([
                ':id_utilisateur' => $id_utilisateur,
                ':adresse'        => $adresse,
                ':total'          => $total,
            ]);
            $id_commande = intval($pdo->lastInsertId());

            $stmtLigne = $pdo->prepare("
                INSERT INTO lignes_commande (id_commande, id_produit, quantite, prix_unitaire)
                VALUES (:id_commande, :id_produit, :quantite, :prix_unitaire)
            ");
            foreach ($panier as $item) {
                $stmtLigne->execute([
                    ':id_commande'   => $id_commande,
                    ':id_produit'    => intval($item['id_produit']),
                    ':quantite'      => intval($item['quantite']),
                    ':prix_unitaire' => floatval($item['prix']),
                ]);
            }
            $pdo->commit();
            return $id_commande;

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function listeCommandes($statut = '', $search = '') {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $where  = [];
        $params = [];

        if (!empty($statut)) {
            $where[] = "c.statut = :statut";
            $params[':statut'] = $statut;
        }
        if (!empty($search)) {
            $where[] = "(u.nom LIKE :s OR u.email LIKE :s2 OR c.id_commande = :id)";
            $params[':s']  = "%$search%";
            $params[':s2'] = "%$search%";
            $params[':id'] = intval($search);
        }

        $sql = "SELECT c.id_commande, c.date_commande, c.statut, c.total,
                       c.adresse_livraison, u.nom, u.email
                FROM commandes c
                JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur"
             . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
             . " ORDER BY c.date_commande DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommande($id) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            SELECT c.*, u.nom, u.email
            FROM commandes c
            JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur
            WHERE c.id_commande = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getLignes($id_commande) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $stmt = $pdo->prepare("
            SELECT l.quantite, l.prix_unitaire, p.nom, p.image
            FROM lignes_commande l
            JOIN produits p ON p.id_produit = l.id_produit
            WHERE l.id_commande = :id
        ");
        $stmt->execute([':id' => $id_commande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changerStatut($id_commande, $statut) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $statutsValides = ['en attente','confirmée','expédiée','livrée','annulée'];
        if (!in_array($statut, $statutsValides)) {
            throw new InvalidArgumentException("Statut invalide : $statut");
        }
        $stmt = $pdo->prepare("
            UPDATE commandes SET statut = :statut WHERE id_commande = :id
        ");
        $stmt->execute([':statut' => $statut, ':id' => $id_commande]);
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