# Zone Bootstrap - PHP-PV

## Pre-Requis

Librairie | Lien | Requis
------------ | ------------- | -------------
JQuery | https://www.jquery.com | Oui
Bootstrap5 | https://www.getbootstrap.com | Oui

## Installation

- Téléchargez le code source de PHP-PV sur GITHUB. Décompressez le fichier **php-pv-master.zip**. Copiez le contenu du dossier **php-pv-master** dans **/**
- Copiez le fichier **jquery-3.x.x.min.js** dans **/js/jquery.min.js**
- Copiez le fichier **jquery-migrate-1.x.x.min.js** dans **/js/jquery-migrate.min.js**
- Décompressez le fichier **bootstrap-5.x.x-dist.zip**. Copiez :
	- **bootstrap-5.x.x-dist/css/bootstrap.min.css** vers **/css/bootstrap.min.css**
	- **bootstrap-5.x.x-dist/js/bootstrap.bundle.min.js** vers **/js/bootstrap.min.js**
- Décompressez le fichier **fontawesome-free-5.x.x-web.zip**. Copiez le contenu du dossier **fontawesome-free-5.x.x-web/** dans **/vendor/fontawesome/**

Vous devez avoir la structure suivante :

```
/php-pv-master
/css
	bootstrap.min.css
/js
	bootstrap.min.js
	jquery-migrate.min.js
	jquery.min.js
/vendor
	fontawesome
```	

Créez votre fichier **/mazone1.php** avec ce contenu :

```php
<?php
// Librairie PHP-PV par defaut
include dirname(__FILE__)."/php-pv-master/Pv/Base.class.php" ;
// Librairie Bootstrap 5
include dirname(__FILE__)."/php-pv-master/Pv/IHM/Bootstrap5.class.php" ;
// Déclarer la classe Application
class MonApplication1 extends \Pv\Application\Application
{
protected function ChargeIHMs()
{
// Inscrire la zone bootstrap dans l'application
$this->InsereIHM('mazone1', new MaZone1) ;
}
}
// Déclarer la zone Bootstrap
class MaZone1 extends \Pv\ZoneBootstrap\ZoneBootstrap
{
public $InclureFontAwesome = 1 ; // Inclure la librairie Font Awesome
public $AccepterTousChemins = 1 ;
protected function ChargeScripts()
{
// Inscrire le script index de la zone
$this->InsereScriptParDefaut(new MonScript1()) ;
}
}
// Déclarer le script index
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function RenduSpecifique()
{
return "Hello, ma zone 1" ;
}
}
// Afficher la zone dans le navigateur
$app = new MonApplication1() ;
$app->Execute() ;

?>
```

Résultat dans un navigateur :

![Resultat zone bootstrap 5](images/zonebootstrap5_apercu1.png)

Voici le code source de cette page dans le navigateur :

![Code source zone bootstrap 5](images/zonebootstrap5_codesource1.png)

## Tableau de données

### Utilisation

La classe tableau de données est **\Pv\ZoneBootstrap\TableauDonnees\TableauDonnees**.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
// Déclaration
$this->Tabl1 = new \Pv\ZoneBootstrap\TableauDonnees\TableauDonnees() ;
// Chargement de la config
$this->Tabl1->AdopteScript("tabl1", $this) ;
$this->Tabl1->ChargeConfig() ;
$this->Tabl1->ToujoursAfficher = 1 ;
// Définition des filtres de sélection
$this->Flt1 = $this->Tabl1->InsereFltSelectHttpGet("expression", "champ1 like concat(<self>, '%')") ;
$this->Flt1->Libelle = "Expression" ;
// Définition des colonnes
$this->Tabl1->InsereDefColCachee("id") ;
$this->Tabl1->InsereDefCol("champ1", "Champ 1") ;
$this->Tabl1->InsereDefCol("champ2", "Champ 2") ;
// Définition du fournisseur de données
$this->Tabl1->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
$this->Tabl1->FournisseurDonnees->BaseDonnees = new MaBD1() ;
$this->Tabl1->FournisseurDonnees->RequeteSelection = "matable1" ;
}
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= $this->Tabl1->RenduDispositif() ;
return $ctn ;
}
}
```

![Tableau de données bootstrap 5](images/zonebootstrap5_tableau.png)

### Propriétés / Méthodes spécifiques

Propriété | Description
------------- | -------------
$ClasseCSSRangee | Classe CSS tableau de la rangée. Par défaut "table-striped"
$ClsBstBoutonSoumettre | Classe CSS du bouton "Rechercher" pour filtrer les résultats. Par défaut "btn-success"
$ClsBstFormFiltresSelect | Classe CSS de la largeur du formulaire des filtres. Par défaut "col-12 col-sm-8 col-md-6"

```php
// Déclaration
$this->Tabl1 = new \Pv\ZoneBootstrap\TableauDonnees\TableauDonnees() ;
// Propriétés spécifiques
$this->Tabl1->ClasseCSSRangee = "table-bordered" ;
$this->Tabl1->ClsBstBoutonSoumettre = "btn-info" ;
// ...
$this->Tabl1->AdopteScript("tabl1", $this) ;
```

## Grille de données

### Utilisation

La classe Grille de données est **\Pv\ZoneBootstrap\TableauDonnees\GrilleDonnees**.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
// Déclaration
$this->Tabl1 = new \Pv\ZoneBootstrap\TableauDonnees\GrilleDonnees() ;
// Chargement de la config
$this->Tabl1->AdopteScript("gril1", $this) ;
$this->Tabl1->ChargeConfig() ;
// Définition des filtres de sélection
$this->Flt1 = $this->Tabl1->InsereFltSelectHttpGet("expression", "champ1 like concat(<self>, '%')") ;
$this->Flt1->Libelle = "Expression" ;
// Définition des colonnes
$this->Tabl1->InsereDefColCachee("id") ;
$this->Tabl1->InsereDefCol("champ1", "Champ 1") ;
$this->Tabl1->InsereDefCol("champ2", "Champ 2") ;
$this->Tabl1->ContenuLigneModele = '<p>${champ1}</p>
<p>Champ 2 : ${champ2}</p>' ;
// Définition du fournisseur de données
$this->Tabl1->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
$this->Tabl1->FournisseurDonnees->BaseDonnees = new MaBD1() ;
$this->Tabl1->FournisseurDonnees->RequeteSelection = "matable1" ;
}
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= $this->Tabl1->RenduDispositif() ;
return $ctn ;
}
}
```

### Propriétés / Méthodes spécifiques

Propriété | Description
------------- | -------------
$ClasseCSSRangee | Classe CSS tableau de la rangée. Par défaut "table-striped"
$ClsBstBoutonSoumettre | Classe CSS du bouton "Rechercher" pour filtrer les résultats. Par défaut "btn-success"
$ClsBstFormFiltresSelect | Classe CSS de la largeur du formulaire des filtres. Par défaut "col-12 col-sm-8 col-md-6"

```php
// Déclaration
$this->Tabl1 = new \Pv\ZoneBootstrap\TableauDonnees\GrilleDonnees() ;
// Propriétés spécifiques
$this->Tabl1->ClasseCSSRangee = "table-bordered" ;
$this->Tabl1->ClsBstBoutonSoumettre = "btn-info" ;
// ...
$this->Tabl1->AdopteScript("tabl1", $this) ;
```

## Formulaire de données

### Utilisation

La classe formulaire de données est **\Pv\ZoneBootstrap\FormulaireDonnees\FormulaireDonnees**.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public $Form1 ;
public $Flt1 ;
public $Flt2 ;
public function DetermineEnvironnement()
{
// Initiation
$this->Form1 = new \Pv\ZoneBootstrap\FormulaireDonnees\FormulaireDonnees() ;
// Toujours afficher le formulaire
$this->Form1->InclureElementEnCours = 0 ;
$this->Form1->InclureTotalElements = 0 ;
// Définir la classe commande "Executer"
// $this->Form1->NomClasseCommandeExecuter = "MaCmdExecScript2" ;
// Liaison avec le script en cours
$this->Form1->AdopteScript("form1", $this) ;
// Chargement de la config
$this->Form1->ChargeConfig() ;
// Définition des autres propriétés
$this->Flt1 = $this->Form1->InsereFltEditHttpPost("champ1") ;
$this->Flt1->Libelle = "Champ 1" ;
$this->Flt2 = $this->Form1->InsereFltEditHttpPost("champ2") ;
$this->Flt2->Libelle = "Champ 2" ;
}
public function RenduSpecifique()
{
$ctn = '' ;
// Rendu du formulaire de donnees
$ctn .= $this->Form1->RenduDispositif() ;
return $ctn ;
}
}
```

![Formulaire de données bootstrap 5](images/zonebootstrap5_formulaire.png)

### Propriétés / Méthodes spécifiques

Propriété | Description
------------- | -------------
$ClasseCSSSucces | Classe CSS Bootstrap du message succès de l'exécution d'une commande. Par défaut "alert alert-primary"
$ClasseCSSErreur | Classe CSS Bootstrap du message erreur de l'exécution d'une commande. Par défaut "alert alert-danger"
$ClasseCSSCommandeExecuter | Classe CSS Bootstrap du bouton "Exécuter". Par défaut "btn-primary"
$ClasseCSSCommandeAnnuler | Classe CSS Bootstrap du bouton "Annuler". Par défaut "btn-danger"

```php
// Initiation
$this->Form1 = new \Pv\ZoneBootstrap\FormulaireDonnees\FormulaireDonnees() ;
// Toujours afficher le formulaire
$this->Form1->InclureElementEnCours = 0 ;
$this->Form1->InclureTotalElements = 0 ;
$this->Form1->ClasseCSSErreur = "btn-warning" ;
$this->Form1->ClasseCSSCommandeExecuter = "btn-info" ;
// ...
$this->Form1->AdopteScript("form1", $this) ;
```

## Dessinateur de filtres

### Utilisation

Le dessinateur de filtres fournit le rendu des filtres selection d'un tableau de données et filtres édition du formulaire de données.
Pour Bootstrap 5, utilisez la classe **\Pv\ZoneBootstrap\DessinFiltres\DessinFiltres**.

```php
class DessinFiltres1 extends \Pv\ZoneBootstrap\DessinFiltres\DessinFiltres
{
}

class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
// Déclaration
$this->Tabl1 = new \Pv\ZoneBootstrap\TableauDonnees\GrilleDonnees() ;
// Chargement de la config
$this->Tabl1->AdopteScript("gril1", $this) ;
$this->Tabl1->ChargeConfig() ;
$this->Tabl1->DessinateurFiltresSelection = new DessinFiltres1() ;
// ...
}
```

### Propriétés principales

Propriété | Description
------------- | -------------
$ColXs | Classe CSS Bootstrap Xs du bloc des filtres pour les petits écrans
$ColSm | Classe CSS Bootstrap Sm du bloc des filtres
$ColMd | Classe CSS Bootstrap Md du bloc des filtres
$ColLd | Classe CSS Bootstrap Ld du bloc des filtres
$UtiliserContainerFluid | Utiliser le container fluid
$EditeurSurligne | Mettre l'éditeur sur une autre ligne
$ColXsLibelle | Classe CSS Bootstrap Xs du libellé
$ClsBstLibelle | Classe CSS Bootstrap complémentaire du libellé
$AlignLibelle | Alignement html du libellé
$ColXsEditeur | Classe CSS Bootstrap complémentaire de l'éditeur
$AlignEditeur | Alignment html de l'éditeur

## Voir aussi

- [L'Application](application.md)
- [La zone web](zoneweb/zoneweb.md)
- [Index](index.md)