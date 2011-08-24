<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Traduction française: butchu, zoneo.net

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/
// Stylesheet
$stylesheet = "../red.css";
if (isset($HTTP_COOKIE_VARS["phpTrafficA_style"])) {
	$stylesheet = "../".$HTTP_COOKIE_VARS["phpTrafficA_style"].".css";
}
if (!is_file($stylesheet)) {
	$stylesheet = "../red.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>Pense-bête pour phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Fermer la fenêtre</a></div>
<h1>Pense-bête pour phpTrafficA</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Liste noire</strong>: ce formulaire sert à placer un site référant sur liste noire. Par exemple, certains moteurs de recherche ne permettent pas l'extraction de mots clés. Ils ne peuvent pas être inclus dans les statistiques de moteurs de recherche et ne sont pas non plus de véritables référants. Cette fonction permet aussi de place tout site pratiquant du <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referrer spam</A> sur liste noire.</li>

<li><A name="domain"></A><strong>Domaine</strong>: entrez la base du domaine dont vous voulez suivre les statistiques, quelque chose comme <code>monserveur.net/mondossier/</code>.</li>

<li><A name="savehost"></A><strong>Derniers accès</strong>: phpTrafficA conserve un tableau contenant les informations complètes sur les derniers visiteurs. Au bout d'un certain temps, ces informations sont tout simplement détruites and l'information reste préservée sous forme pré-analysée. Ce tableau ne sert que pour les fonctions concernant les derniers visiteurs.</li>

<li><A name="oslist"></A><strong>Systèmes d'exploitation</strong>: liste de systèmes d'exploitation. Ce tableau contient la liste des systèmes d'exploitation reconnus par phpTrafficA et la chaine de caractères permettant de les identifier. Chaque ligne contient les informations sur un système d'exploitation et sa signature, séparés par le symbole <code>|</code>. Ce tableau peut être mis à jour lors d'une mise à jour de phpTrafficA.</li>

<li><A name="public"></A><strong>Public</strong>: tous les visiteurs peuvent voir les statistiques d'accès au sites <code>publics</code>. Les statistiques d'accès au sites <code>privés</code> ne sont visibles qu'après avoir entré le mot de passe administrateur.</li>

<li><A name="selist"></A><strong>Liste de moteurs de recherche</strong>: ce tableau contient la liste des moteurs de recherche, comment les détecter, et comment extraire les mots clés. Chaque ligne contient le nom de moteur, son url, et la variable utilisée pour passer les mots clés (séparées par un symbole <code>:</code> s'il y a plusieurs possibilités), le tout séparés par des symboles <code>|</code>. Par exemple, une recherche sur Google sera détectée par l'URL <code>google.com</code> et les mots clés dans la variable <code>q</code>. Ce tableau peut être mis à jour lors d'une mise à jour de phpTrafficA.</li>

<li><A name="table"></A><strong>Nom du tableau</strong>: sera utilisé comme racine pour tous les tableaux SQL créés pour ce site par phpTrafficA. Il commenceront toujours par cette racine.</li>

<li><A name="trim"></A><strong>URL simplifiés</strong>: si cette fonction est activée, les URL contenant des requêtes PHP seront simplifiés. Par exemple, <code>index.php?lang=fr</code> et <code>index.php?lang=en</code> seront tous deux enregistrés comme <code>index.php</code>. Cette la méthode recommandée dans phpTrafficA. Vous pouvez bien sûr la désactiver et séparer les statistiques pour des pages comme <code>index.php?lang=fr</code> et <code>index.php?lang=en</code>. Attention cepandant, vous obtiendrez un très grand nombre de pages pour des sites très dynamiques. De plus, <strong>il est fortement déconseillé de changer ce paramètre une fois l'enregistrement commencé</strong>.</li>

<li><A name="wblist"></A><strong>Liste de navigateurs</strong>: ce tableau présent la liste de navigateurs reconnus dans phpTrafficA et comment les identifier. Chaque ligne contient les informations sur un navigateur et sa signature, séparés par le symbole <code>|</code>. Ce tableau est mis à jour lors d'une mise à jour de phpTrafficA.</li>

<li><A name="countbots"></A><strong>Compter les robots</strong>: Si vous activez cette option, les robots comme googlebot ou yahoo slurp seront comptés comme visiteurs dans vos statistiques. Dans le cas contraire, ils seront ignorés et apparaîtront seulement dans le tableau des dernières visites, c'est tout.</li>

<li><A name="counter"></A><strong>Compteur</strong>: si vous sélectionnez cette fonction, phpTrafficA agira comme un compteur, c'est à dire qu'il indiquera le nombre de visites vers une page (sous forme d'image ou de texte) à chaque appel.</li>

<li><A name="magnetindex"></A><strong>Attractivité</strong>: l'index d'<code>attractivité</code> sert à estimer le trafic amené par une page donnée. Par exemple, une page avec un index de 1, 3, ou 3 amène 10, 100, et 1000 visites par jour, respectivement. Attention! Ne confondez pas cet index avec le nombre de visites vers cette page. Dans ce calcul, on compte toutes les pages vues de tous les visiteurs ayant commencé leur visite sur cette page.

</li>

<li><A name="bouncerate"></A><strong>Taux de rebond</strong>: le <code>taux de rebond</code> est une mesure importante. Il vous donne le pourcentage ayant quitté votre site immédiatement après y être arrivé. En général, une page avec un taux de rebond important (90%) peut avoir une mauvaise présentation, ou encore attirer des visiteurs avec des mots clés ne correspondant pas tout à fait au contenu.</li>

<li><A name="sereferrers"></A><strong>Les moteurs de recherche sont aussi des affluents</strong>: Si vous sélectionnez cette option, les moteurs de recherche seront aussi comptés comme affluents. L'avantage: vous aurez les URL complets des recherches menant à votre site. Inconvénient: votre tableau des affluents risque de devenir énorme! A utiliser avec prudence...</li>

<li><A name="visitcutoff"></A><strong>Fin de visite pour visiteur unique (en minutes)</strong>: Cette option permet de fixer le temps de fin de visite pour un visiteur unique (en minutes). Si un visiteur donné n'a pas été actif pendant un temps supérieur à cette valeur, tout nouveau click depuis cette adresse IP sera considéré comme une nouvelle visite. La valeur par défaut est 15 minutes.</li>

<li><A name="timediff"></A><strong>Décalage horaire </strong>: cette option sera utile si l'heure sur le serveur n'est pas la même que l'heure sur le site suivi. Indiquez le décalage entre le site et le serveur, en heures.</li>

<li><A name="URLTrimFactor"></A><strong>Longueur des URL </strong>: cette option sert à raccourcir les chaînes de caractères dans les URL. La valeur par défaut est 10, avec un valeur de 20, les URL seront deux fois plus longs, avec une valeur de 5, ils seront deux fois plus courts.</li>

<li><A name="referrerNewDuration"></A><strong>Temps pour garder les affluents marqués comme "nouveaux"</strong>: les nouveaux affluents seront marqués comme <code>nouveaux</code> jusqu'à ce que vous suiviez le lien. Les affluents plus anciens que ce réglage ne seront pas marqués comme <code>nouveaux</code>, même si vous n'avez pas cliqué sur le lien.</li>

<li><A name="autoCleanRKIP"></A><strong>Nettoyage automatique des affluents, mots clé, liste d'adresses IP, et chemins</strong>: si vous activez cette option, les tableaux des listes d'affluents, mots clé, adresses IP, et chemins seront nettoyés régulièrement. Cette option retirera toutes les entrées plus vieilles que 2 mois et qui n'ont été enregistrées qu'une seule fois.</li>

<li><A name="autoCleanAccess"></A><strong>Nettoyage automatique des tableaux d'accès</strong>: si vous activez cette option, les tableaux d'accès seront nettoyés régulièrement. Cette option retirera toutes les données plus anciennes que deux mois. Le nombre total de visiteurs vers chaque page et les statistiques pour le site complet seront préservés.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>