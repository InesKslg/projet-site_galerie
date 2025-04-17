Description :
-
Un site galerie conçu pour afficher des photos, gérer les utilisateurs, et offrir une interface intuitive. 
Ce projet inclut un backend et un frontend, configurés pour fonctionner dans un environnement Docker.


Dépendances :
-
Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :
Docker : Installation de Docker
Docker Compose : Installation de Docker Compose


Structure du Projet :
-

Voici les principaux fichiers et leur rôle :
-
Dockerfile : Définit l'image Docker pour exécuter le projet.

docker-compose.yml : Configuration multi-conteneurs pour le site (serveur web, base de données, etc.).

Dossier www :
-
Contient le code source du site.
Fichiers PHP comme index.php, login.php pour la logique du backend.
Dossier style pour les fichiers CSS.
Dossier media-img pour les images.
Dossier uploads pour les fichiers uploadés.
Dossier utilisateur pour la gestion des utilisateurs.

Pour construire vos serveurs dockers, veuillez suivre les étapes suivantes :
-
1 Ajoutez votre base de données dans le répertoire "build_mariadb".

2 Éditez le fichier "Dockerfile" qui se trouve dans le répertoire "build_mariadb".

3 Dans la ligne 2 de ce fichier, éditez la ligne en ajoutant le nom de la base de données 
  Exemple : "COPY galeriephoto.sql /docker-entrypoint-initdb.d/"

4 L'ensemble de vos projets sera stocké dans le répertoire "site".

5 Créez et nommer un dossier contenant le dossier www et les deux fichiers dockers

6 Ouvrez le terminal, entrer la commande : " cd c:\chemind_vers_cerépertoire\docker_web "

7 tapez les deux commandes suivantes : 
  Commande 1 : " docker-compose build "
  Commande 2 : " docker-compose up -d "


L'accès à vos projets se fait en écrivant "localhost" dans la barre de recherche du navigateur.

Concernant la base de données, vous y accédez grâce au lien :1200 ou directement par http://localhost:1200/  dans l'URL.

