# Actions Web

## Définition

Une Action Web est un ensemble d'instructions qui s'exécute dans la Zone Web. Elle ne se limite pas à afficher un contenu HTML, comme les scripts web.
Elle peut également :
- déclencher le téléchargement d'un fichier
- renvoyer un fichier RSS, JS ou CSS
- renvoyer une réponse JSON
- exécuter un code précis, avant d'afficher le script web

## Déclaration

Vous pouvez déclarer les actions dans plusieurs méthodes :

Objet | Méthode	Contexte | Description
------------- | ------------- | -------------
Zone web | InsereActionPrinc($nom, $action) | Utiliser dans la méthode **ChargeConfig()** | Les actions principales s'exécutent avant d'identifier le script en cours
Zone web | InsereActionAvantRendu($nom, $action) | Utiliser dans la méthode **ChargeConfig()**. | S'exécutent avant d'afficher le script en cours
Script web | InsereActionAvantRendu($nom, $action) | Utiliser dans la méthode **DetermineEnvironnement()** | Déclare l'action uniquement lorsque le script doit être affiché. Le nom de l'action sera basé sur l'ID Instance du script et le nom de l'action.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
	public function DetermineEnvironnement()
	{
		$this->InsereActionAvantRendu("action1", new MonAction1()) ;
	}
}
```

**\Pv\ZoneWeb\Action\Action** est la classe de base. Réécrivez la méthode **Execute()** pour personnaliser l'exécution.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\Action
{
	public function Execute()
	{
		echo "Action appelee" ;
	}
}
```

La zone web exécute une action web à partir du paramètre GET **appelleAction**.

```
?appelleScript=script1&appelleAction=action1
```

## Propriétés / Méthodes principales

Propriété / Méthode | Description
------------- | -------------
$ZoneParent | Référence la zone qui contient l'action
$ScriptParent | Référence le script qui contient l'action. Si vous avez ajouté sur la zone, cette propriété sera nulle.
$ApplicationParent | Référence l'application globale
ObtientUrl($params=array()) | Renvoie l'URL de l'action
ObtientUrlFmt($params=array(), $autresParams=array()) | Renvoie l'URL de l'action, sans appliquer l'encodage HTTP sur $params. $autresParams est encodé. C'est idéal pour les liens des Tableaux de Données.
Invoque($params=array(), $valeurPost=array(), $async=1) | Appelle l'URL de l'action, avec les paramètres GET $params et POST $valeurPost. Si $async=1, cette requête est asynchrone.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\Action
{
public function Execute()
{
$titreDoc = $this->ZoneParent->Titre ;
}
}
```

## Types d'action

Classe | Description | Utilisation
------------- | ------------- | -------------
\Pv\ZoneWeb\Action\Notification | Exécute des instructions et garde le résultat (succès/echec et message d'exécution) | Réécrire la méthode **Execute()**
\Pv\ZoneWeb\Action\ResultatJson ou \Pv\ZoneWeb\Action\EnvoiJson | Affiche un contenu JSON dans le navigateur | Réécrire la méthode **Execute()**. A l'intérieur, définissez la propriété Resultat. Cette propriété sera le retour JSON.
\Pv\ZoneWeb\Action\TelechargeFichier | Démarre le téléchargement du fichier | Réécrire la méthode **Execute()**

### Utilisation \Pv\ZoneWeb\Action\Notification

Vous devez réécrire la méthode **Execute()**. A l'intérieur, utiliser ces méthodes pour définir le résultat :
- **ConfirmeSucces($msg)**
- **RenseigneErreur($msg)**
Dans le script ou la zone, utilisez la propriété **TypeErreur** et méthode **ObtientMessage()** de l'instance Action pour afficher le résultat. Pour tester si l'action a ramené un résultat, utilisez la méthode **PossedeMessage()**

```php
class MonAction1 extends \Pv\ZoneWeb\Action\Notification
{
public function Execute()
{
$this->ConfirmeSucces("Action invoqu&eacute; avec succ&egrave;s") ;
}
}

class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
$this->Action1 = $this->InsereActionAvantRendu("action1", new MonAction1()) ;
// ...
}
public function RenduSpecifique()
{
	$ctn = '' ;
	if($this->Action1->PossedeMessage())
	{
		$ctn .= '<div class="color:blue">'.$this->Action1->ObtientMessage().'</div>' ;
	}
	$ctn .= '<p><a href="'.$this->Action1->ObtientUrl().'">Valider le menu</a></p>' ;
}
}
```

### Utilisation \Pv\ZoneWeb\Action\ResultatJson

Renseignez la propriété **Resultat** dans la fonction **ConstruitResultat** pour définir le nom du fichier téléchargé.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\ResultatJson
{
protected function ConstruitResultat()
{
$this->Resultat->id = 11 ;
$this->Resultat->valeur1 = "Livre" ;
}
}
```

### Utilisation \Pv\ZoneWeb\Action\TelechargeFichier

Renseignez la propriété **NomFichierAttache** dans la fonction **DetermineFichierAttache** pour définir le nom du fichier téléchargé.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\TelechargeFichier
{
protected function DetermineFichierAttache()
{
$this->NomFichierAttache = "resultats.txt" ;
}
}
```

Réécrivez la méthode **AfficheContenu** pour envoyez le contenu du fichier. A l'intérieur, utilisez les fonctions PHP **echo**.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\TelechargeFichier
{
protected function AfficheContenu()
{
echo "texte à afficher..." ;
}
}
```
- Si le fichier existe déjà, utilisez **CheminFichierSource** pour le charger.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\TelechargeFichier
{
protected function DetermineFichierAttache()
{
$this->NomFichierAttache = "resultats.txt" ;
$this->CheminFichierSource = dirname(__FILE__)."/mondossier/resultats.txt" ;
}
}
```

- Si vous voulez renseigner des entêtes spécifiques, réécrivez la méthode **AfficheEntetes**

```php
class MonAction1 extends \Pv\ZoneWeb\Action\TelechargeFichier
{
protected function AfficheEntetes()
{
Header("HTTP/1.1 404 Not found") ;
Header("MonEntete1:Valeur") ;
}
}
```

### Utilisation \Pv\ZoneWeb\Action\RedirigeFichier

Cette action redirige vers un fichier. Si vous avez des problèmes d'encodage avec l'action **\Pv\ZoneWeb\Action\TelechargeFichier**, utilisez cette classe.

Renseignez la propriété **CheminFichierSource** dans la fonction **DetermineFichierSource** pour le chemin du fichier à atteindre.

```php
class MonAction1 extends \Pv\ZoneWeb\Action\TelechargeFichier
{
protected function DetermineFichierSource()
{
$this->CheminFichierSource = "files/resultats.txt" ;
}
}
```

## Voir aussi

- [Zone web](zoneweb.md)
- [Scripts web](scripts.md)

