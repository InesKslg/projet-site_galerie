<?php
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $host = 'db_lamp';
    $user = 'user';
    $password = 'user';
    $dbname = 'test';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Récupérer les données du formulaire
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $mdp = isset($_POST['password']) ? $_POST['password'] : '';

    // Validation des champs
    if (empty($login) || empty($mdp)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\W).{8,}$/', $mdp)) {
        $message = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un caractère spécial.";
    } else {
        // Vérifier si le login existe déjà
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login");
            $stmt->execute(['login' => $login]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $message = "Ce login est déjà utilisé.";
            } else {
                // Hachage du mot de passe
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                // Insérer l'utilisateur dans la base de données
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, mot_de_passe) VALUES (:login, :mot_de_passe)");
                $stmt->execute(['login' => $login, 'mot_de_passe' => $mdp_hash]);

                // Connexion automatique après l'inscription
                $_SESSION['utilisateur'] = ['login' => $login];

                // Redirection vers la page utilisateur
                header("Location: ../utilisateur/index-utilisateur.php");
                exit();
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
        
        h2 {
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

        p {
            font-size: 1rem;
            color: var(--text-color);
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>

        <!-- Affichage du message -->
        <?php if (!empty($message)) : ?>
            <p class="message <?php echo strpos($message, 'réussie') !== false ? 'success' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form method="POST" action="register.php">
            <label for="login">Identifiant :</label>
            <input type="text" id="login" name="login" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
    </div>
</body>
</html>
