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
 
   ### La base de données
  
  j'ai créé l'entité /src/Entity/Image.php 
  Nous aurons donc une table "Product" et une table "image" liées par une relation "oneToMany"

  au moment où on va faire un persiste au niveau de l'entité Product il va devoir également injecter les données concernant les images qu'on aura ajouté,
  du coup j'ai ajouté sur la annotation cascade={"persist"};
  https://github.com/bill1ben/Trico.com/blob/617cd85fc2e775c3331ce27630c980b9fbe5e5ba/src/Entity/Product.php#L45
  
   ### le form
  
      https://github.com/bill1ben/Trico.com/blob/617cd85fc2e775c3331ce27630c980b9fbe5e5ba/src/Form/ProductType.php#L28
    
   ### dossier upload
  
