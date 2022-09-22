# Scripts Web

La zone web contient des scripts, qui renvoient un contenu spécifique en fonction d'un paramètre GET (appelleScript par défaut).

Les scripts varient le contenu d'une zone, tout en gardant les mêmes entêtes et pieds de document HTML.

## Utilisation

Vous devez déclarer chaque script, et réécrire sa méthode de rendu.

Insérez les dans la zone, à partir des méthodes **ChargeScripts** ou **ChargeScriptsMembership**.

```php
// 
class MaZoneWeb1 extends \Pv\ZoneWeb\ZoneWeb
{
// Inscription des scripts
public function ChargeScripts()
{
// Inscrire la page d'accueil
$this->InsereScriptParDefaut(new MonScriptAccueil()) ;
// Inscrire la page d'a propos
$this->InsereScript("a_propos", new MonScriptAPropos()) ;
// Inscrire la page de contact
$this->InsereScript("contact", new MonScriptContact()) ;
}
// ...
}
// Déclaration du script d'accueil
class MonScriptAccueil extends \Pv\ZoneWeb\Script\Script
{
public function RenduSpecifique()
{
return "<p>Page d'accueil</p>" ;
}
}
// Déclaration du script a propos
class MonScriptAPropos extends \Pv\ZoneWeb\Script\Script
{
public function RenduSpecifique()
{
return "<p>A propos de mon site</p>" ;
}
}
// Déclaration du script de contact
class MonScriptContact extends \Pv\ZoneWeb\Script\Script
{
public function RenduSpecifique()
{
return "<p>Ma page de contact</p>" ;
}
}
```

## Propriétés / Méthodes principales

Membre | Description
------------- | -------------
$IDInstanceCalc | ID Unique du script parmi les objets créés
$ZoneParent | Accède à la zone web contenant le script
$NomElementZone | Nom du script dans la zone
$ApplicationParent | Accède à l'application contenant le script
$TitreDocument | Titre du document HTML sur le navigateur
$MotsCleMeta | Mots clés méta HTML
$DescriptionMeta | Description méta HTML
$ViewportMeta | Viewport méta HTML
$Titre | Titre du script, utilisé dans le corps du document HTML

```php
// Déclaration du script
class MonScriptAPropos extends \Pv\ZoneWeb\Script\Script
{
public $TitreDocument = "A propos de mon site" ;
public $Titre = "A propos" ;
public $MotsCleMeta = "A propos, informations, relativement, concernant" ;
public $DescriptionMeta = "Trouvez sur cette page des informations sur mon site" ;
}
```

## Environnement du script

Le script possède la méthode **DetermineEnvironnement**(), pour définir les variables nécessaires au rendu.

```php
// Déclaration du script
class MonScriptDetailArticle extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
$bd = new MaBD() ;
$this->ParamId = intval($_GET["id"]) ;
$this->LgnPrinc = $bd->FetchSqlRow('select * from article where id=:id', array("id" => $this->ParamId)) ;
if(count($this->LgnPrinc) > 0)
{
// Définir les entêtes HTML à partir des variables environnement
$this->TitreDocument = "Article ".htmlentities($this->LgnPrinc["titre"]) ;
$this->Titre = "Infos article ".htmlentities($this->LgnPrinc["titre"]) ;
}
// Insérer du CSS uniquement sur ce script
$this->ZoneParent->InsereContenuCSS("h1 { color: red ; }") ;
// Insérer du Javascript uniquement sur ce script
$this->ZoneParent->InsereContenuJs("function test1() { alert("OK") ; }") ;
}
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= "<p>ID : ".$this->LgnPrinc["id"]."</p>" ;
$ctn .= "<p>Titre : ".htmlentities($this->LgnPrinc["titre"])."</p>" ;
$ctn .= "<p>PU : ".$this->LgnPrinc["PU"]." Eur.</p>" ;
return $ctn ;
}
}
```

## Gestion du rendu

Pour le rendu, voici les membres principaux de la classe script :

Membre | Type | Description
------------- | ------------- | -------------
$InclureRenduTitre | bool | Confirme ou Annule le rendu du titre pour le script en cours
$InclureRenduDescription | bool | Confirme ou Annule le rendu du titre pour le script en cours

```php
// Déclaration du script
class MonScriptDetailArticle extends \Pv\ZoneWeb\Script\Script
{
public $InclureRenduTitre = false ;
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= "Ce script sera affiché sans titre" ;
return $ctn ;
}
}
```
Vous pouvez réecrire certaines méthodes pour personnaliser le rendu :

Membre | Type | Description
------------- | ------------- | -------------
RenduChemin() | string | Contenu HTML de l'arborescence du script
RenduTitre() | string | Contenu HTML spécifique du script
RenduDescription() | string | Contenu HTML spécifique du script
RenduSpecifique() | string | Contenu HTML spécifique du script
RenduDispositifBrut() | string | Contenu HTML brut du script. Par défaut, cette méthode appelle les méthodes RenduChemin(), RenduTitre(), RenduDescription(), RenduSpecifique().

```php
// Déclaration du script
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function RenduTitre()
{
$ctn = '' ;
$ctn .= "<h1>Ma page web</h1>" ;
return $ctn ;
}
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= "Ce script sera affiché avec un titre très grand !" ;
return $ctn ;
}
}
```

## Impression

Pour rendre une page imprimable, déclarez sa propriété *$Imprimable* à true.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public $Imprimable = true ;
}
```

Lors du rendu, le lien d'impression est disponible dans l'action web **$ActionImprime**.

Utilisez également la méthode **ImpressionEnCours()** pour masquer les contenus à l'impression.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public $Imprimable = 1 ;
public function RenduSpecifique()
{
$ctn = '' ;
$ctn .= '<p>Voici un script imprimable</p>' ;
// Afficher le bouton si la page n'est pas en mode impression
if(! $this->ImpressionEnCours())
{
$ctn .= '<p><a href="'.$this->ActionImprime->ObtientUrl().'">Imprimer</a></p>' ;
}
return $ctn ;
}
}
```

Personnalisez ainsi les styles d'impression dans **DetermineEnvironnement()**.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public $Imprimable = 1 ;
public function DetermineEnvironnement()
{
// Mettre une taille de police 12px pendant l'impression
if($this->ImpressionEnCours())
{
$this->ZoneParent->InsereContenuCSS("body {
font-size:12px ;
}") ;
}
}
}
```

## Voir aussi

- [Zone web](zoneweb.md)
- [Entêtes de document](entetedoc.md)
- [Scripts web](scripts.md)
- [Documents web](documents.md)
- [Actions](actions.md)
- [Filtres de données](filtresdonnees.md)
- [Tableaux de données](tableauxdonnees.md)
- [Formulaires de données](formulairedonnees.md)
- [Scripts membership](scriptsmembership.md)
