# Entêtes document

## Propriétés HTML

La zone web possède des propriétés pour le rendu HTML.

Propriété | Rôle | Contenu HTML généré
------------- | ------------- | -------------
EncodageDocument | Fixe l'encodage de la page web. Par défaut : "utf-8" | &lt;meta charset="$valeur" /&gt;
MotsCleMeta | Mots clé META | &lt;meta name="keywords" value="$valeur" /&gt;
DescriptionMeta | Description META | &lt;meta name="description" value="$valeur" /&gt;
LangueDocument | Langage du document | &lt;html lang="$valeur"&gt;
TitreDocument | Titre du document | &lt;title&gt;$valeur&lt;/title&gt;
ViewportMeta | Viewport Meta | &lt;meta name="viewport" content="$valeur" /&gt;
UrlBase | Lien de Base | &lt;base href="$valeur" /&gt;

Exemple :
```php
<?php
	// Déclaration de la zone web
	class ZoneWebApplication1 extends \Pv\ZoneWeb\ZoneWeb
	{
		public $EncodageDocument = 'utf-8' ;
		public $MotsCleMeta = 'Attributs, Zone, Web Simple' ;
		public $DescriptionMeta = 'Description d\'une Zone Web Simple' ;
	}
?>
```

Vous pouvez completer le rendu <head> avec la propriété $RenduExtraHead.

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
	public $RenduExtraHead = '<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">' ;
}
```

## CSS et Javascript

### Méthodes principales

La zone peut insérer du contenu CSS et JS avec ces méthodes.

Méthode | Description
------------- | -------------
InscritContenuCSS ($contenu) | Insère un tag &lt;style&gt; avec le $contenu
InscritLienCSS ($href) | Insère un tag &lt;link rel="stylesheet" type="text/css" href="$href" /&gt;
InscritContenuJs ($contenu) | Insère un tag &lt;script&gt; avec le $contenu
InscritContenuJsCmpIE ($contenu, $versionMin=9) | Insère un tag &lt;script&gt; avec le $contenu, avec les directives IE
InscritLienJs ($src) | Insère un tag &lt;script&gt; avec la source $src
InscritLienJsCmpIE ($src, $versionMin=9) | Insère un tag &lt;script&gt; avec la source $src, avec les directives IE

Veuillez réécrire la méthode **InclutLibrairiesExternes()**, en invoquant la méthode parente.

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
Protected function InclutLibrairiesExternes()
{
Parent::InclutLibrairiesExternes() ;
// Inscrire les autres librairies JS & CSS…
$this->InscritContenuCSS("body { text-align:center ; }") ;
$this->InscritLienJs("js/main.js") ;
} 
}
```

Elle inclut automatiquement les scripts & styles CSS des librairies Javascript populaires.

### JQuery

Propriété | Spécification
------------- | -------------
$InclureJQuery | Mettre à 1 pour inclure la librairie jquery
$CheminJQuery | Chemin relatif du fichier Js jQuery. Par défaut : "js/jquery.min.js" 
$InclureJQueryMigrate | Mettre à 1 pour inclure la librairie jquery-migrate 1.x
$CheminJQueryMigrate | Chemin relatif du fichier Js JQueryMigrate. Par défaut : "js/jquery-migrate.min.js"
$InclureJQueryMigrate3 | Mettre à 1 pour inclure la librairie jquery-migrate 3.x
$CheminJQueryMigrate3 | Chemin relatif du fichier Js JQueryMigrate. Par défaut : "js/jquery-migrate.min.js"

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
public $InclureJQuery = 1 ;
public $CheminJQuery = "vendor/jquery/jquery.min.js" ;
}
```

### JQuery UI

Propriété | Spécification
------------- | -------------
$InclureJQueryUi | Mettre à 1 pour inclure la librairie jqueryui
$CheminJsJQueryUi | Chemin relatif du fichier Js JQuery Ui. Par défaut : "js/jquery-ui.min.js"
$CheminCSSJQueryUi | Chemin relatif du fichier CSS jQuery Ui. Par défaut : "css/jquery-ui.css"

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
public $InclureJQueryUi = 1 ;
public $CheminJsJQueryUi = "vendor/jquery-ui/jquery-ui.min.js" ;
public $CheminCSSJQueryUi = "vendor/jquery-ui/jquery-ui.css" ;
}
```

### Bootstrap

Propriété | Spécification
------------- | -------------
$InclureBootstrap | Mettre à 1 pour inclure la librairie bootstrap
$CheminJsBootstrap | Chemin relatif du fichier Js Bootstrap. Par défaut : "js/bootstrap.min.js"
$CheminCSSBootstrap | Chemin relatif du fichier CSS Bootstrap. Par défaut : "css/bootstrap.css"
$InclureBootstrapTheme | Mettre à 1 pour inclure un thème personnalisé Bootstrap
$CheminCSSBootstrapTheme | Chemin relatif du fichier CSS Bootstrap. Par défaut : "css/bootstrap-theme.min.css"

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
public $InclureBootstrap = 1 ;
public $CheminJsBootstrap = "vendor/bootstrap/bootstrap.min.js" ;
public $CheminCSSBootstrap = "vendor/bootstrap/bootstrap.min.css" ;
}
```

### Font Awesome

Propriété | Spécification
------------- | -------------
$InclureFontAwesome | Mettre à 1 pour inclure Font Awesome
$CheminFontAwesome | Chemin relatif du fichier CSS Font Awesome. Par défaut : "css/font-awesome.css"

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
public $InclureFontAwesome = 1 ;
public $CheminFontAwesome = "vendor/fontawesome/css/all.min.css" ;
}
```

## Voir aussi

- [Zone web](zoneweb.md)
- [Scripts web](scripts.md)
- [Documents web](documents.md)
