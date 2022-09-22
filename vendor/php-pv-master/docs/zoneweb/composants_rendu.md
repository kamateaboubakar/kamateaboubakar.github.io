# Composants IU

Les composants IU permettent d'interagir avec les utilisateurs.

## Utilisation

Vous devez suivre ce procédé :

1. Initier le composant
```php
$comp = new \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees() ;
```
2. Renseigner ses propriétés d'initiation, s'il en possède
```php
$comp->InscrireCommandeExecuter = 1 ;
```
3. Adoptez le script ou la zone contexte par les méthodes AdopteScript($nom, & $script) ou AdopteZone($nom, $zone).
```php
$comp->AdopteScript("monComposant", $this) ;
```
4. Charger la configuration du composant par la méthode ChargeConfig()
```php
$comp->ChargeConfig() ;
```
5. Renseigner ses autres propriétés
```php
$comp->CommandeExecuter->Libelle = "VALIDER" ;
$comp->SuccesMessageExecution = "La page a été modifiée" ;
```
6. Invoquer le Rendu du composant par la méthode RenduDispositif()
```php
$ctn = $comp->RenduDispositif() ;
```

## Définition

Vous devez déclarer les composants IU dans la zone web, le document web ou le script web.
Pour le définir (étape 1. à 5 de l'utilisation), utilisez ces méthodes :

Classe | Méthode | Directives
------------- | ------------- | -------------
Document Web | PrepareRendu(& $zone) | Aucun
Zone Web | DetermineEnvironnement(& $script) | Invoquer parent::DetermineEnvironnement($script) après avoir défini le composant
Script Web | DetermineEnvironnement() | Aucun

Vous invoquez le rendu séparément :

Classe | Méthode | Directives
------------- | ------------- | -------------
Document Web | RenduEntete(& $zone) | Invoquer parent::RenduEntete($zone) avant le rendu du composant
Document Web | RenduPied(& $zone) | Invoquer parent::RenduPied($zone) après le rendu du composant
Zone Web | RenduContenuCorpsDocument () | Aucun
Script Web | protected RenduDispositifBrut() | Aucun
Script Web | RenduSpecifique() | Aucun

```php
// Cas d'un script
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
// Déclaration
$this->Tabl1 = new \Pv\ZoneWeb\TableauDonnees\TableauDonnees() ;
// Chargement de la config
$this->Tabl1->AdopteScript("tabl1", $this) ;
$this->Tabl1->ChargeConfig() ;
}
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= $this->Tabl1->RenduDispositif() ;
return $ctn ;
}
}
```

## Types de composant

### Données

Nom | Classe | Rôle
------------- | ------------- | -------------
Tableau de données Html | \Pv\ZoneWeb\TableauDonnees\TableauDonnees | Affiche sous forme de tableau des données
Grille de données Html | \Pv\ZoneWeb\TableauDonnees\GrilleDonnees | Affiche sous forme de grille des données
Répéteur de données Html | PvRepeteurDonneesHtml | Similaire à la grille de données, sans organiser par ligne / colonne
Formulaire de données Html | \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees | Affiche sous forme de formulaire de données

### Graphiques et statistiques

Nom | Classe | Rôle
------------- | ------------- | -------------
Chart pChart | \Pv\ZoneWeb\PChart\Pchart | Chart réalisée avec la librairie PHP pChart 2.0

### Sliders

Nom | Classe | Rôle
------------- | ------------- | -------------
Slider JQuery Camera | \Pv\ZoneWeb\SliderJs\JQueryCamera | Slider réalisé à partir de la librairie Javascript jQuery Camera

## Voir aussi

- [Tableaux de données](tableauxdonnees.md)
- [Formulaires de données](formulairedonnees.md)
- [Le composant ChartJS](chartjs.md)
- [Zone web](zoneweb.md)
- [Scripts web](scripts.md)
- [Documents web](documents.md)
- [Filtres de données](filtresdonnees.md)
