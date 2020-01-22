# PROJET SMASH2

Antoine PINOT  
Léo FELIX  
Simon DAL PONTE  
Hugo JAHNKE  

# INSTALATION
- dans le dossier **app**, executer la commande `composer install` pour installer les dépendances.  
configurer le serveur wamp ou apache pour qu'il pointe vers le fichier src/public/index.php
- dans la racine du projet, executer la commande `docker-compose up`

# IMPORT DE LA BASE DE DONNEES
Pour accéder à la base de donnée via phpMyAdmin, se connecter à l'adresse http://localhost:5000
importer le fichier **db/smash2.sql**

identifiant par défaut : 
 - Serveur : *database*
 - Utilisateur : *Smash2*
 - Mot de passe : *Smash2*

Sinon, configurer les identifiants de connexion dans le fichier **src/config/config.inc.php**

Pour accéder à l'application, se connecter à l'adresse http://localhost:8080

# compte admins
le compte administrateur par défault est :

**login :** root  
**password :** root  
