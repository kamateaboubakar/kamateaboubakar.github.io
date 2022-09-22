# Les filtres de données http

## Présentation

PHP-PV utilise des composants de rendu, qui affichent des formulaires. Les filtres de données proposent des champs de saisie et collecte leurs valeurs après soumission. Ils identifient également les paramètres à impacter sur les bases de données (nom de colonne, condition sql...)

## Propriétés et Méthodes principales

Propriété / Méthode | Description
------------- | -------------
$Libelle | Libellé
$EstEtiquette | Si la valeur est 1, le filtre affichera la valeur au lieu du champ de saisie.
$ValeurVide | Valeur NULLE du filtre.
$ValeurParDefaut | Valeur par défaut
$NePasLierParametre | Renvoie toujours la valeur par défaut du filtre.
$NomParametreLie | Nom du paramètre soumis par http
$NePasLireColonne | N'utilise pas la valeur de la colonne liée sur le paramètre. Utilisée dans les formulaires de données.
$AliasParametreDonnees | Expression pour decoder la valeur de la colonne liée. Ex. UNHEX(&lt;self&gt;)
$ExpressionDonnees | Condition SQL lorsque le filtre est utilisé dans une recherche. Ex : MON_CHAMP = &lt;self&gt;
$NomColonneLiee | Nom de la colonne dans la table, pour un filtre d'édition
$ExpressionColonneLiee | Expression de la colonne dans la table, pour un filtre d'édition. Ex. HEX(&lt;self&gt;)
$LectureSeule | Passer la valeur par défaut du filtre de données, et la soumettre dans le formulaire.
$Invisible | Le filtre ne sera pas affiché sur la page. Il renvoie toujours sa valeur par défaut
$NePasIntegrerParametre | Empêche le formulaire de données d'utiliser ce filtre pour la recherche.
Lie() | Définit la valeur soumise à partir du formulaire. Elle est utilisée après clic sur une commande de formulaire donnée ou le bouton « Rechercher » du tableau de données
$DejaLie | Signale si le filtre a été lié auparavant.
$ValeurParametre | Valeur liée. Utilisez plutôt la méthode Lie().
$Role | Type du filtre de données.
$TypeLiaisonParametre | Contient la valeur "get", valeur issue de $_GET ou "post", valeur issue de $_POST

```php
$form = new \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees() ;
// ...
$flt = $form->InsereFltEditHttpPost("champ1", "champ1") ;
// Figer le filtre en lecture seule
$flt->EstEtiquette = true ;
// Encoder lors de l'enregistrement
$flt->ExpressionColonneLiee = "HEX(BASE64_ENCODE(<self>))" ;
// Décoder la valeur de la colonne champ1
$flt->AliasParametreDonnees = "BASE64_DECODE(UNHEX(champ1))" ;
```

## Correcteur de valeur

C'est une propriété qui encode/décode la valeur brute d'un filtre.
Vous devez étendre la classe **\Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\Correcteur** et réécrire les méthodes clées.

```php
class MonCorrectValFiltre1 extends \Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\CorrecteurValeur
{
public function Applique($valeur, & $filtre)
{
return htmlentities($valeur) ;
}
}

class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
// ...
$form = new \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees() ;
// ...
$flt1 = $form->InsereFltEditHttpPost("flt1", "") ;
$flt1->CorrecteurValeur = new MonCorrectValFiltre1() ;
}
}
```

Il existe des correcteurs de valeurs déjà déclarés.

Classe | Description
------------- | -------------
\Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\Correcteur | Correcteur de valeur par défaut
\Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\SansAccent | Enlève tous les caractères spéciaux.

```php
$form = new \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees() ;
// ...
$flt1 = $form->InsereFltEditHttpPost("flt1", "") ;
$flt1->CorrecteurValeur = new \Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\SansAccent() ;
```

## Composant de filtre

### Présentation

Le composant de filtre de données est le champ de saisie. Vous le définissez ainsi :

Méthode | Description
------------- | -------------
DeclareComposant($nomClasseComposant) | Définit le composant à partir du nom de la classe
RemplaceComposant($composant) | Définit le composant à partir de l'instance

Exemple :
```php
$flt1 = $form->InsereFltEditHttpPost("monchamp") ;
// Le composant est dans la variable $comp1
$comp1 = $flt1->DeclareComposant("\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne") ;
```

### Composants Eléments HTML

Classe | Description
------------- | -------------
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte | Composant par défaut affectée au filtre. Affiche un champ INPUT
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne | Affiche un champ TEXTAREA
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse | Affiche un champ PASSWORD
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEtiquette | Affiche un champ en lecture seule.

### Composants de liste

Les composants de liste utilisent un fournisseur de données pour leur rendu.

```php
$comp1 = $flt1->DeclareComposant("\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect") ;
// Définition du fournisseur de données
$comp1->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
$comp1->FournisseurDonnees->BaseDonnees = new MaBD1() ;
$comp1->FournisseurDonnees->RequeteSelection = "matable1" ;
// Définition des valeur
$comp1->NomColonneValeur = "id" ;
$comp1->NomColonneLibelle = "monchamp1" ;
// Afficher une valeur par defaut s'il n'y a aucune valeur
$comp1->InclureElementHorsLigne = 1 ;
$comp1->ValeurElementHorsLigne = -1 ; 
$comp1->LibelleElementHorsLigne = " – Aucun --" ;
```

Classe | Description
------------- | -------------
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteSelect | Affiche une zone SELECT
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteOptionsRadio | Affiche une zone de plusieurs options RADIO à cocher.
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteOptionsCocher | Affiche une zone de plusieurs options CHECKBOX à cocher. Pour récupérer toutes les valeurs cochées, utilisez la propriété $ValeurBrute du filtre.
\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCadreOptionsRadio | Affiche une zone de plusieurs options RADIO à cocher, qui sont dans un IFRAME HTML

## Formatage de libellé

Si le filtre de données est en étiquette, son champ de saisie ne sera pas éditable.
Pour personnaliser ce rendu, utilisez la méthode **DefinitFmtLbl**. Etendez la classe **\Pv\ZoneWeb\FiltreDonnees\FormatLbl\FormalLbl** et réécrivez sa méthode **Rendu($valeur, & $composant)**.

```php
class MonFmtLbl1 extends \Pv\ZoneWeb\FiltreDonnees\FormatLbl\FormalLbl
{
public function Rendu($valeur, & $composant)
{
return base64_decode($valeur) ;
}
}
```

Ensuite, affectez ce format au composant avec la méthode **DefinitFmtLbl()** du filtre. Vous devez déclarer le composant avant d'utiliser cette méthode.

```php
$comp = $flt1->DeclareComposant("\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte") ;
// …
$flt1->DefinitFmtLbl(new MonFmtLbl1()) ;
```

Voici des formats déjà définis :

Classe | Description
------------- | -------------
\Pv\ZoneWeb\FiltreDonnees\FormatLbl\FormalLbl | Classe de base.
\Pv\ZoneWeb\FiltreDonnees\FormatLbl\Web | Classe affectée par défaut
\Pv\ZoneWeb\FiltreDonnees\FormatLbl\DateFr | Affiche au format date français
\Pv\ZoneWeb\FiltreDonnees\FormatLbl\DateTimeFr | Affiche au format date et heure français
\Pv\ZoneWeb\FiltreDonnees\FormatLbl\Monnaie | Affiche au format monétaire

```php
$comp = $flt1->DeclareComposant("\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte") ;
// …
$flt1->DefinitFmtLbl(new \Pv\ZoneWeb\FiltreDonnees\FormatLbl\Monnaie) ;
```

## Le filtre de données Upload

Le filtre de données Upload télécharge un fichier.

### Propriétés / Méthodes principales

Propriété / Méthodes | Description
------------- | -------------
$NettoyerCaractsFichier | Enlève les caractères spéciaux du nom fichier téléchargé.
$ExtensionsAcceptees | Tableau contenant les extensions uniquement acceptées. Si le fichier soumis n'a pas une extension, il ne sera pas copié dans le répertoire 
$ExtensionsRejetees | Tableau contenant les extensions à rejeter systématiquement.
$FormatFichierTelech | Format du nom de fichier téléchargé. 
$SourceTelechargement | Contient les valeurs "post" si aucun fichier n'est soumis ou "files" si un fichier a été soumis.
$InfosTelechargement | Contient les détails du fichier téléchargé.
$ToujoursRenseignerFichier | Renvoie une erreur dans le formulaire de données, si aucun fichier n'est soumis.

```php
$form = new \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees() ;
// ...
$flt1 = $form->InsereFltEditHttpUpload("chemin_livre", "upload/livres", "chemin_livre") ;
$flt->ExtensionsAcceptees = array("pdf", "docx", "doc") ;
```

### Variables Format de fichier téléchargé

Les variables disponibles sont :
- **Cle** : Identifiant Unique
- **NombreAleatoire** : Nombre compris entre 1 et 10000
- **NomFichier** : Nom d'origine du fichier
- **Timestamp** : Timestamp actuel
- **Date** : Date au format YmdHis

```php
$flt1->FormatFichierTelech = "Bon-Commande-${Cle}" ;
```

### Caractéristiques du Composant

Le composant par défaut de ce filtre est le composant **\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneUpload**.
Ses propriétés principales sont :

Propriété | Description
------------- | -------------
$InclureErreurTelecharg | Afficher l'erreur survenue lors du téléchargement
$InclureCheminCoteServeur | Afficher le chemin relatif du fichier téléchargé
$InclureZoneSelectFichier | Afficher les informations sur le fichier téléchargé
$CheminCoteServeurEditable | Autoriser la modification du chemin relatif sur le serveur
$InclureApercu | Définit l'affichage de l'aperçu.
$LargeurCadreApercu | Largeur HTML du cadre d'aperçu
$HauteurCadreApercu | Hauteur HTML du cadre d'aperçu.

```php
$comp = $flt1->ObtientComposant() ;
$comp->CheminCoteServeurEditable = true ;
```

Valeurs possibles pour $InclureApercu :
- 0 : Ne pas autoriser d'aperçu
- 1 : Affiche un lien pour afficher dans le navigateur
- 2 : Afficher le fichier dans un cadre, si c'est possible

```php
$comp = $flt1->ObtientComposant() ;
$comp->InclureApercu = 1 ;
```

## Voir aussi

- [Composants de rendu](composants_rendu.md)
- [Tableaux de données](tableauxdonnees.md)
- [Formulaires de données](formulairedonnees.md)
- [Le composant ChartJS](chartjs.md)
- [Zone web](zoneweb.md)
- [Scripts web](scripts.md)
- [Scripts membership](scriptsmembership.md)
