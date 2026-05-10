<?php
// Commande.class.php
require_once 'connexion.php';

class Commande {

    private $pdo;

    public function __construct() {
        $cnx = new connexion();
        $this->pdo = $cnx->CNXbase();
    }

    /* Créer une commande + ses lignes en une transaction */
    public function creerCommande(int $id_utilisateur, string $adresse, float $total, array $panier): int {
        $this->pdo->beginTransaction();
        try {
            // 1. Insérer la commande
            $stmt = $this->pdo->prepare("
                INSERT INTO commandes (id_utilisateur, date_commande, statut, adresse_livraison, total)
                VALUES (:id_utilisateur, NOW(), 'en attente', :adresse, :total)
            ");
            $stmt->execute([
                ':id_utilisateur' => $id_utilisateur,
                ':adresse'        => $adresse,
                ':total'          => $total,
            ]);
            $id_commande = intval($this->pdo->lastInsertId());

            // 2. Insérer les lignes
            $stmtLigne = $this->pdo->prepare("
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

            $this->pdo->commit();
            return $id_commande;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /* Liste toutes les commandes (admin) */
    public function listeCommandes(string $statut = '', string $search = ''): array {
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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Détail d'une commande */
    public function getCommande(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.nom, u.email
            FROM commandes c
            JOIN utilisateurs u ON u.id_utilisateur = c.id_utilisateur
            WHERE c.id_commande = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /* Lignes d'une commande */
    public function getLignes(int $id_commande): array {
        $stmt = $this->pdo->prepare("
            SELECT l.quantite, l.prix_unitaire, p.nom, p.image
            FROM lignes_commande l
            JOIN produits p ON p.id_produit = l.id_produit
            WHERE l.id_commande = :id
        ");
        $stmt->execute([':id' => $id_commande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Changer le statut d'une commande */
    public function changerStatut(int $id_commande, string $statut): void {
        $statutsValides = ['en attente','confirmée','expédiée','livrée','annulée'];
        if (!in_array($statut, $statutsValides)) {
            throw new InvalidArgumentException("Statut invalide : $statut");
        }
        $stmt = $this->pdo->prepare("
            UPDATE commandes SET statut = :statut WHERE id_commande = :id
        ");
        $stmt->execute([':statut' => $statut, ':id' => $id_commande]);
    }

    /* Statistiques par statut (admin) */
    public function statsParStatut(): array {
        $stmt = $this->pdo->query("
            SELECT statut, COUNT(*) as nb, SUM(total) as ca
            FROM commandes GROUP BY statut
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>