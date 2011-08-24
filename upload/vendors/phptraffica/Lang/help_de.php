<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Deutsche Übersetzung von Roland Schuller

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
<title>phpTrafficA Hilfe</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Fenster schlie&szlig;en</a></div>
<h1>phpTrafficA Hilfe</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Schwarze Liste</strong>: Dieses Formular wird benutzt, um fehlerhafte Verweise (Referrer) zu entfernen, z.B.: Es gibt Suchmaschinen, die es nicht erlauben, die verwendete Suchphrase zu extrahieren. Diese können dann auch nicht in der Suchmaschinenstatistik angezeigt werden, und sie können und dürfen auch nicht als Verweis angezeigt werden. Die Funktion wird auch benutzt, um Seiten, die <A href="http://de.wikipedia.org/wiki/Referrer-Spam" target="_blank">Referrer Spam</A> benutzen, auszuschliessen.</li>

<li><A name="domain"></A><strong>Domain</strong>: Geben Sie hier den Domainnamen an, den Sie beobachten wollen, z.B.: <code>meinserver.de/meinverzeichnis/</code>.</li>

<li><A name="savehost"></A><strong>Letzte Besucher</strong>: phpTrafficA speichert eine Liste mit allen Informationen der letzten Besucher. 
Nach einer gewissen Zeit werden sie um Speicher zu sparen gel&ouml;scht. Die Daten bleiben dabei in verarbeiteter Form erhalten. Die Informationen werden in <code>Letzte Besucher</code> bei der <code>Pfadanalyse</code> verwendet. Wenn Sie mehr Informationen vorhalten wollen, erh&ouml;hen Sie einfach die Anzahl der gespeicherten Besucher. phpTrafficA kann bei grosser Anzahl gespeicherter Besuche langsam werden.</li>

<li><A name="oslist"></A><strong>OS Liste</strong>: Liste der Betriebssysteme. Die Tabelle beinhaltet eine Liste der Betriebssysteme und ihre Erkennungsmerkmale f&uuml;r die Browseridentifikation. Jede Zeile enh&auml;lt einen Identifikationstext gefolgt vom Betriebsystemnamen. Die beiden Werte werden durch <code>|</code> getrennt. Diese Tabelle wird bei Upgrades von phpTrafficA erweitert.</li>

<li><A name="public"></A><strong>&Ouml;ffentlich</strong>: Der Zugriff zu <code>&Ouml;ffentlichen</code> Domains ist f&uuml;r jeden Besucher gestattet. Der Zugriff zu <code>Privaten</code> Domains wird nur angemeldeten Nutzern gestattet.</li>

<li><A name="selist"></A><strong>Suchmaschinenliste</strong>: Die Tabelle enth&auml;lt eine Liste der Suchmaschinen, ihre Erkennungsmerkmale und Angaben zur Extraktion der Suchphrasen. Jede Zeile enth&auml;lt die Informationen f&uuml;r eine Suchmaschine. Die Informationen werden durch <code>|</code> getrennt. Der erste Eintrag beinhaltet den Namen, der Zweite ist ein Text, um die Suchmaschine zu identifizieren und der Dritte beinhaltet den Variablennamen, in dem die Suchphrase &uuml;bergeben wurde (mehrere M&ouml;glichkeiten werden durch <code>:</code> getrennt). Beispiel: Die Suchmaschine Google wird duch den Text <code>google.com</code> erkannt, die Variable <code>q</code> gibt die Suchphrase an =&gt; <code>Google|google.com|q</code>. Diese Tabelle wird bei Upgrades von phpTrafficA erweitert.</li>

<li><A name="table"></A><strong>Tabelle</strong>: Prefix f&uuml;r SQL Tabellen. F&uuml;r jede &uuml;berwachte Webseite werden mehrere Tabellen angelegt. Der Name der Tabellen beginnt mit dem hier angegebenen Text.</li>

<li><A name="trim"></A><strong>URL Abschneiden</strong>: Wenn Sie diese Option auf <code>Ja</code> setzten, werden die URL's in der Statistik abgeschnitten, z.B.: <code>index.php?lang=de</code> und <code>index.php?lang=en</code> werden als <code>index.php</code> gespeichert. Das ist das Standardverhalten von phpTrafficA. Seien Sie vorsichtig, wenn Sie die gesamte URL speichern lassen. Dies kann zu vielen Eintr&auml;gen f&uuml;r die unterschiedlichen Kombinationen bei dynamischen Webseiten f&uuml;hren. <strong>Es wird nicht empfohlen, diese Option zu &auml;ndern, nachdem phpTrafficA eine Zeitlang gelaufen ist.</strong></li>

<li><A name="wblist"></A><strong>Webbrowserliste</strong>: Liste der verschiedenen Webbrowser. Jede Zeile enh&auml;lt einen Identifikationstext gefolgt vom Browsernamen. Die beiden Werte werden duch <code>|</code> getrennt. Diese Tabelle wird bei Upgrades von phpTrafficA erweitert.</li>

<li><A name="countbots"></A><strong>Z&auml;hle Suchmaschinen</strong>: Wenn diese Option gewählt wurde, werden Suchmaschinen (Googlebot, Yahoo Slurp, etc.) als normale Besucher gezählt. Ist diese Option nicht gewählt, ercheinen sie nicht in der Statistik. Sie werden nur in der Liste der letzten Besucher angezeigt.</li>

<li><A name="counter"></A><strong>Z&auml;hler</strong>: Wenn diese Option gew&auml;hlt wurde, fungiert phpTrafficA auch als Z&auml;hler. Wenn Sie eines der Bilderscripte f&uuml;r die Aufzeichnung verwenden, wird das Bild die Anzahl der Treffer seit Beginn der Aufzeichnung darstellen. Wenn Sie die reine PHP L&ouml;sung zum Z&auml;hlen der Treffer verwenden (kein Bild), wird phpTrafficA die Anzahl der Treffer f&uuml;r diese Seite (als Text) ausgeben.</li>

<li><A name="magnetindex"></A><strong>Magnet Index</strong>: Der <code>Magnet Index</code> ist ein n&uuml;tzliches Werkzeug. Er gibt Auskunft &uuml;ber H&auml;figkeit des Besuchseinstiegs &uuml;ber diese Seite, z.B: Seiten mit einem <code>Magnet Index</code> von 1, 2 und 3 sind Einstiegsseiten mit 10, 100, 1000 direkten Aufrufen pro Tag. Verwechseln Sie diese Nummer nicht mit den Aufrufen einer Seite. Es werden nur Aufrufe gez&auml;hlt, wenn dies die erste Seite eines Besuches ist.</li>

<li><A name="bouncerate"></A><strong>Abprallrate</strong>: Die <code>Abprallrate</code> ist ein Ma&szlig; daf&uuml;r, wie viele Besucher nach dem Ansehen dieser Seite Ihre Homepage wieder verlassen haben.</li>

<li><A name="sereferrers"></A><strong>Suchmschinen sind Verweise</strong>: Wenn diese Option aktiviert ist, werden Suchmaschinenanfragen in der Verweisliste aufgef&uuml;hrt. Das erlaubt Ihnen die volle Kontrolle der Suchanfragen, die zu Ihrer Webseite f&uuml;hrten. Hierdurch kann jedoch die Verweistabelle sehr gro&szlig; werden. Seien Sie vorsichtig, wenn Sie diese Option benutzen und nur wenig Speicher haben.</li>

<li><A name="visitcutoff"></A><strong>Besuchsende</strong>: Diese Option beschreibt das Besuchsende in Minuten. Wenn ein einzelner Besucher (basierend auf seiner IP Adresse) l&auml;nger als die hier angegebene Zeit nicht aktiv war, danach wieder eine Seite aufruft, so wird das als neuer Besuch gewertet. Standardeinstellung ist 15 Minuten.</li>

<li><A name="timediff"></A><strong>Zeitunterschied</strong>: verwenden sie diese Option, wenn sich Ihr Server nicht in der gleichen Zeitzone wie Ihre Webseite befindet. Geben sie hier den Zeitunterschied in Stunden an.</li>

<li><A name="URLTrimFactor"></A><strong>URL-abschneiden Faktor</strong>: Diese Option dient zur Verkürzung Zeichenketten in URLs. Der Standardwert ist 10, mit einem Wert von 20, werden die URLs doppelt so lang, mit einem Wert von 5, werden sie nur halb so lang.</li>

<li><A name="referrerNewDuration"></A><strong>Zeit um Verweise als "neu" zu bezeichnen</strong>: Neue Adressen auf der Verweisseite werden als <code>neu</code> bezeichnet bis der Link besucht wird. Verweise, die älter als die Zeit sind, werden nicht mehr als neu bezeichnet, auch wenn sie noch nicht besucht wurden.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatisches Aufräumen von Verweisen, Suchbegriffen, IP Liste und Verläufen</strong>: Falls diese Option aktiviert wird, werden die Tabellen mit Verweisen, Suchbegriffen, IP Adressen und Verläufen regelmäßig bereinigt. Dies betrifft Einträge, die älter als 2 Monate sind, oder nur einmal benutzt wurden.</li>

<li><A name="autoCleanAccess"></A><strong>Automatisches Aufräumen der Zugriffsliste</strong>: Falls diese Option aktiviert wird, werden die Tabellen mit Zugriffen (Seitenaufrufe und Besucherzahl) regelmäßig bereinigt. Dies betrifft Einträge, die älter als 2 Monate sind. Die Gesamtzahl der Zugriffe auf jede Seite und die Statistik für die gesamte Domain bleibt gespeichert, aber alle Zugriffsinformationen für individuelle Seiten werden gelöscht wenn sie älter als 2 Monate sind.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>