<?php
function Verifier_session() {
    if (!isset($_SESSION['id_utilisateur'])) {
        header("location:login.php");
        exit();
    }
}

function Verifier_admin() {
    if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'admin') {
        header("location:login.php");
        exit();
    }
}
?>
