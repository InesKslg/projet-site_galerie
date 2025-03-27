<?php
// Démarrage de la session pour gérer la connexion de l'utilisateur
session_start();

// Paramètres de connexion à la base de données
$host = 'db_lamp';
$user = 'user';
$password = 'user';
$dbname = 'test';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'connexion') {
        $login = isset($_POST['login_connexion']) ? trim($_POST['login_connexion']) : '';
        $mdp = isset($_POST['mdp_connexion']) ? $_POST['mdp_connexion'] : '';

        if (empty($login) || empty($mdp)) {
            $message = "Tous les champs sont obligatoires.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login");
            $stmt->execute(['login' => $login]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur && password_verify($mdp, $utilisateur['mot_de_passe'])) {
                $_SESSION['utilisateur'] = $utilisateur;
                header("Location: ../utilisateur/index-utilisateur.php");
                exit();
            } else {
                $message = "Login ou mot de passe incorrect.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /* Définition des couleurs principales */
        :root {
            --primary-color:rgb(203, 232, 248); /* Bleu clair */
            --secondary-color:rgb(230, 156, 223); /* Rose vif */
            --background-color: #f4f1f3; /* Fond gris clair */
            --error-bg-color: #f8d7da; /* Fond rouge clair pour erreurs */
            --success-bg-color: #d4edda; /* Fond vert clair pour succès */
            --text-color: #333; /* Couleur du texte principale */
            --white-color: #fff; /* Blanc */
            --highlight-color: #f1c3ed; /* Rose pâle pour mise en avant */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: var(--white-color);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Image de fond semi-transparente */
        body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("../media-img/image-italie.jpg") no-repeat center center fixed;
        background-size: cover;
        opacity: 0.3;
        z-index: -1;
        }
        
        h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        label {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: var(--text-color);
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f9f9f9;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        button {
            padding: 12px;
            background-color: var(--primary-color);
            color: var(--white-color);
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        .message {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 1rem;
        }

        .message.success {
            background-color: var(--success-bg-color);
            color: #4CAF50;
        }

        .message.error {
            background-color: var(--error-bg-color);
            color: #e11a2e;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <?php if ($message): ?>
            <div class="message <?= (strpos($message, 'Erreur') !== false) ? 'error' : 'success' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="login_connexion">Login :</label>
            <input type="text" id="login_connexion" name="login_connexion" required>

            <label for="mdp_connexion">Mot de passe :</label>
            <input type="password" id="mdp_connexion" name="mdp_connexion" required>

            <button type="submit" name="action" value="connexion">Se connecter</button>
        </form>
    </div>
</body>
</html>
