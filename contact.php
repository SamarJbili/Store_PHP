<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #ffffff; color: #000000; font-family: 'Inter', sans-serif; }
        .contact-container { max-width: 800px; margin: 60px auto; }
        
        .form-control {
            border-radius: 0;
            border: 1px solid #000;
            padding: 12px;
            font-size: 0.9rem;
            background-color: transparent;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #000;
            background-color: #fafafa;
        }
        
        .btn-send {
            background: #000;
            color: #fff;
            border-radius: 0;
            border: 1px solid #000;
            padding: 12px 40px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-send:hover {
            background: #fff;
            color: #000;
        }

        .contact-info {
            border-top: 1px solid #eee;
            margin-top: 50px;
            padding-top: 30px;
        }
        
        label {
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container contact-container">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-uppercase" style="letter-spacing: 3px;">Contact</h1>
        <hr class="mx-auto" style="width: 50px; border-top: 2px solid #000; opacity: 1;">
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <form action="contact_traitement.php" method="POST">
                <div class="mb-4">
                    <label>Nom complet</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label>Adresse E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label>Sujet</label>
                    <input type="text" name="sujet" class="form-control">
                </div>

                <div class="mb-4">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="6" required></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-send">Envoyer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="contact-info row text-center small text-uppercase" style="letter-spacing: 1px;">
        <div class="col-md-4 mb-3">
            <span class="fw-bold d-block mb-1">Localisation</span>
            Sfax, Tunis
        </div>
        <div class="col-md-4 mb-3">
            <span class="fw-bold d-block mb-1">Téléphone</span>
            +216 24 45 71 56
        </div>
        <div class="col-md-4 mb-3">
            <span class="fw-bold d-block mb-1">E-mail</span>
            contact@SRshop.com
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>