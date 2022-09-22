# Documents Web

Un document web personnalise l'affichage complet de chaque script.
Dans la zone, il est utile pour différencier le design :
- de la page d'accueil
- des scripts pour impressions
- des scripts qui d'une boîte de dialogue

## Déclaration

Chaque document web hérite de la classe **\Pv\ZoneWeb\Document\Html**.

Veuillez réecrire les méthodes **PrepareRendu(& $zone)**, **RenduEntete(& $zone)** et **RenduPied(& $zone)**.
Vous pouvez manipuler le script sélectionné avec **$zone->ScriptPourRendu**

```php
class MonDocumentWeb1 extends \Pv\ZoneWeb\Document\Html
{
public function PrepareRendu(& $zone)
{
// Garder la préparation du document web html
parent::PrepareRendu($zone) ;
// Inclure des librairies CSS du document
$zone->InsereLienCSS("css/style.css") ;
// Inclure des librairies Js du document
$zone->InsereLienJs("js/main.js") ;
}
public function RenduEntete(& $zone)
{
$ctn = '' ;
// Retourne le contenu HTML jusqu'au tag body
$ctn .= parent::RenduEntete($zone) ;
return $ctn ;
} 
public function RenduPied(& $zone)
{
$ctn = '' ;
// Retourne le contenu HTML à partir de la fin du tag body
$ctn .= parent::RenduPied($zone) ;
return $ctn ;
} 
}
```

## Intégration dans la zone web

D'abord, vous devez mettre la propriété **UtiliserDocumentWeb** à 1.
Ensuite, déclarez chaque document dans la méthode **ChargeConfig()** de la zone web.

```php
class MaZoneWeb extends \Pv\ZoneWeb\ZoneWeb
{
public $UtiliserDocumentWeb = 1 ;
public function ChargeConfig()
{
Parent::ChargeConfig() ;
// Le 1er document web créé est utilisé pour
// tous les scripts
$this->DocumentsWeb["defaut"] = new MonDocumentWeb1() ;
$this->DocumentsWeb["impression"] = new MonDocumentWeb2() ;
}
}
```

Le 1er document web déclaré sera utilisé par défaut pour tous les scripts. Dans le cas ci-dessus, c'est le document web "defaut".

## Affectation à un script

Pour définir le document web du script, renseignez la propriété **NomDocumentWeb** du script.

```php
class MonScriptWeb3 extends \Pv\ZoneWeb\Script\Script
{
// …
public $NomDocumentWeb = "impression" ;
// …
}
```

## Voir aussi

- [Zone web](zoneweb.md)
- [Entêtes de document](entetedoc.md)
