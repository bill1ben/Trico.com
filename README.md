# Trico.com
## vision globale
un site web sous symfony 4.4 (lts) où tout le monde peut s'inscrire et ajouter des t-shirts à vendre.


Loading Fixtures
================

    composer require orm-fixtures --dev
    $php bin/console doctrine:fixtures:load
    
    des jeux de fausses données sont mises en place sur l'application vous pouvez le trouver sur src/DataFixtures.php .
    
 
           
* afin d'avoir des données plus réaliste (des prénoms, des noms de famille, etc..) j'ai utilisé la librairie **FAKER** 
* La librairie **Slugify** nous fournit des opérations de transformation de chaines de caractères a un équivalent compatible en URL
  * Par exemple : "C'est vraiment super" devient "c-est-vraiment-super".
  
   
