# Trico.com


vision globale
================
un site web sous symfony 4.4 (lts) où tout le monde peut s'inscrire et ajouter des t-shirts à vendre.


Loading Fixtures
================

    composer require orm-fixtures --dev
    $php bin/console doctrine:fixtures:load
    
    des jeux de fausses données sont mises en place sur l'application vous pouvez le trouver sur src/DataFixtures.php .
    
 
           
* afin d'avoir des données plus réaliste (des prénoms, des noms de famille, etc..) j'ai utilisé la librairie **FAKER** 
* La librairie **Slugify** nous fournit des opérations de transformation de chaines de caractères a un équivalent compatible en URL
  * Par exemple : "C'est vraiment super" devient "c-est-vraiment-super".
* j'ai utilisé l'API RandomUser.me qui nous fourni des données de profils utilisateurs de façon aléatoire dont notamment des avatars
* 

 Cycle de vie d'une entité  
 ==========================
    *céation, mise à jour et suppression .
     chaque enregistrement est une instance de la classe Entité dont il est issu*
  
mon but et de faire en sorte à chaque fois quand je cherche à sauvegarder une entité de type product (l'entitè t-shirt) il faut que elle-même se prend en main et vérifie si elle a un Slug . si elle ne l'a pas alors elle va le créer.

https://github.com/bill1ben/Trico.com/blob/ba6a0fa2cd41e6572213a6fcdb4c819a1b57959f/src/Entity/Product.php#L83

sans oublier la annotation @ORM\HasLifecycleCallbacks()
  
 upload images
 =============
     afin que chaque produit à ses propres images j'ai dû gérer l'upload multiple 
     
  non seulement que les images doit être enregistré sur le dossier upload/ mais il faut aussi stocker leur nom dans la base de données
  
   ### La base de données
  
     j'ai créé l'entité /src/Entity/Image.php 
     Nous aurons donc une table "Product" et une table "image" liées par une relation "oneToMany"

  au moment où on va faire un persiste au niveau de l'entité Product il va devoir également injecter les données concernant les images qu'on aura ajouté,
  du coup j'ai ajouté sur la annotation cascade={"persist"};
  https://github.com/bill1ben/Trico.com/blob/617cd85fc2e775c3331ce27630c980b9fbe5e5ba/src/Entity/Product.php#L45
  
   ### le form
 
  https://github.com/bill1ben/Trico.com/blob/617cd85fc2e775c3331ce27630c980b9fbe5e5ba/src/Form/ProductType.php#L28
    
   ### dossier upload
   
  sur le dossier public j'ai créé le dossier uploads/
  au niveau du parameters sur service.yaml j'ai ajouté un parameters pour le chemin d'accès au dossier uploads 
  https://github.com/bill1ben/Trico.com/blob/916f711b6e5891548389f630081eaa354d2540a4/config/services.yaml#L7
   
   ### ajouter, modifier, supprimer des images
   

* la fonction new : https://github.com/bill1ben/Trico.com/blob/916f711b6e5891548389f630081eaa354d2540a4/src/Controller/ProductController.php#L103
* La edite : https://github.com/bill1ben/Trico.com/blob/916f711b6e5891548389f630081eaa354d2540a4/src/Controller/ProductController.php#L185
* delet : https://github.com/bill1ben/Trico.com/blob/916f711b6e5891548389f630081eaa354d2540a4/src/Controller/ProductController.php#L245
  
  le fichier js permet de suprimer les images en faisant des requête ajax : https://github.com/bill1ben/Trico.com/blob/master/public/js/images.js
  
 User
 ====
    - Sur le site il doit y avoir des utilisateurs afin qu'ils puissent ajouter supprimer des produits.
    - il doit y avoir aussi des pages profils avec leur prénom leur nom , photo de profile.. et tous les produits partager par l'utilisateur.
    - un utilisateur peut commenter un produit et donner leur avis..
    - utilisateurs peuvent modifier leur nom, prénom leur profil en général et le mot de passe **hasher** sur la base de donnè 
 
  ### base de données
    pouvoir gérer des utilisateurs j'ai créé l'entité /src/Entity/User.php 
 
  Les utilisateurs du site pourront créer des annonces, on leur donne donc une relation OneToMany avec l'entité product
  
  ### Les Encodeurs
     il permettent de hasher le mot de passe les mot de pass utilisateurs
  
  
  j'ai déclaré les encodeurs sur security.yaml afin d'utiliser l'algorithme bcrypt spécifiquement sur App\Entity\User
  https://github.com/bill1ben/Trico.com/blob/325c2deb3d190c8bef8f167ca8e727ab696df4d9/config/packages/security.yaml#L2
  
  #### documentation UserPasswordEncoderInterface symfony : 
  https://symfony.com/doc/current/security.html#c-encoding-passwords
  
  UserPasswordEncoderInterface est une injection de dépendance elle passe par la fonction construct 
  https://github.com/bill1ben/Trico.com/blob/325c2deb3d190c8bef8f167ca8e727ab696df4d9/src/DataFixtures/AppFixtures.php#L19
  
  Si l'on veut que le composant de Sécurité de Symfony comprenne et puisse utiliser notre entité User comme celle qui représente     les utilisateurs de notre système, il faut que l'entité User implémente une interface particulière : **la UserInterface**
  
  https://github.com/bill1ben/Trico.com/blob/325c2deb3d190c8bef8f167ca8e727ab696df4d9/src/Entity/User.php#L23
  
  ADMIN ROLE
  ==========
  (a venir)
  
