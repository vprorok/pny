<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Nederlandse vertaling door Peter Borst --- ragger.nl

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
<title>phpTrafficA helpdesk</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Sluit venster</a></div>
<h1>phpTrafficA helpdesk</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Zwarte lijst</strong>: in deze lijst verschijnen niet goed functionerende referers. Bijvoorbeeld: sommige zoekmachines staan niet toe dat zoekwoorden worden getoond die zijn gebruikt om de website te vinden. Deze zoekmachines komen niet in de zoekmachinestatistieken, maar zijn ook geen referers en mogen dus niet als zodanig worden geteld. De zwarte lijst wordt ook gebruikt om sites uit te sluiten die zich schuldig maken aan <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referer spam</A>.</li>

<li><A name="domain"></A><strong>Domein</strong>: vul hier de hoofdpagina in van het domein dat gevolgd moet worden, bijvoorbeeld <code>mijnwebsite.nl/homepage/</code>.</li>

<li><A name="savehost"></A><strong>Laatste bezoekers</strong>: phpTrafficA houdt een tabel bij met de volledige gegevens van de laatste bezoekers. Na enige tijd worden die verwijderd om ruimte te maken, de informatie wordt bewaard in een lijst. De tabel wordt gebruikt in het onderdeel <code>Laatste bezoekers</code> en voor het verwerken van gegevens in de <code>Route-analyse</code>. Als je meer gegevens wilt zien in deze functies, verhoog dan het aantal opgeslagen bezoekers. phpTrafficA kan extreem traag worden met grote tabellen.</li>

<li><A name="oslist"></A><strong>Lijst van besturingssystemen</strong>: overzicht van besturingssystemen. Deze tabel toont de lijst met besturingssystemen en hoe ze gedetecteerd worden. Elke regel bevat een identificatiestring, gevolgd door de naam van het besturingssysteem, van elkaar gescheiden door het teken <code>|</code>. Deze lijst wordt bijgewerkt bij elke upgrade van phpTrafficA.</li>

<li><A name="public"></A><strong>Openbaar</strong>: alle bezoekers hebben inzage in de statistieken van <code>openbare</code> domeinen. Statistieken voor <code>priv&eacute;</code>-domeinen zijn alleen zichtbaar na inloggen.</li>

<li><A name="selist"></A><strong>Zoekmachines</strong>: lijst van zoekmachines, hoe ze worden ge&iuml;dentificeerd en hoe zoekwoorden uit de referer-string worden gehaald. Elke regel bevat informatie over een zoekmachine, het teken <code>|</code> fungeert als scheidingsteken. Als eerste is de naam vermeld, vervolgens een string waarmee de zoekmachine wordt gedetecteerd in de referer-URL en als laatste de variabele die deze zoekmachine gebruikt om zoekwoorden door te geven (meerdere variabelen worden van elkaar gescheiden door een <code>:</code>). Bijvoorbeeld: een zoekactie met Google wordt gedetecteerd door de URL <code>google.com</code> en de variabele <code>q</code>. Deze lijst wordt bijgewerkt bij elke upgrade van phpTrafficA.</li>

<li><A name="table"></A><strong>Tabelnaam</strong>: basisnaam voor sql-tabellen. Voor elke website die gevolgd wordt door phpTrafficA, worden enkele tabellen gecreëerd. De naam ervan begint telkens met de string die hier wordt ingevuld.</li>

<li><A name="trim"></A><strong>Inkorten URL</strong>: als dit op <code>ja</code> gezet wordt, worden URL's in de statistieken ingekort. Bijvoorbeeld, <code>index.php?lang=nl</code> en <code>index.php?lang=en</code> worden allebei opgeslagen als <code>index.php</code>. Dit is de standaardinstelling in phpTrafficA. Wees voorzichtig met het bewaren van de volledige URL's: het aantal URL-combinaties kan immens groot worden voor geheel dynamische websites. Daarnaast <strong>is het af te raden deze parameter te veranderen als phpTrafficA al enige tijd in gebruik is</strong>.</li>

<li><A name="wblist"></A><strong>Lijst van browsers</strong>: overzicht van webbrowsers. Deze lijst toont de browsers en hoe ze worden ge&iuml;dentificeerd. Elke regel bevat een identificatiestring, gevolgd door naam van de browser, van elkaar gescheiden door het teken <code>|</code>. Deze lijst wordt bijgewerkt bij elke upgrade van phpTrafficA.</li>

<li><A name="countbots"></A><strong>Bots tellen</strong>: Als je deze optie selecteert, worden (ro)bots (als Googlebot, Yahoo Slurp, etc.) geteld als gewone bezoekers. Als je deze optie niet selecteert, worden ze niet opgenomen in de statistieken en zijn ze alleen zichtbaar in de lijst met laatste bezoekers.</li>

<li><A name="counter"></A><strong>Teller</strong>: Als deze optie geselecteerd wordt, zal phpTrafficA tevens functioneren als teller. Bij gebruik van een van de scripts met afbeelding, wordt in de afbeelding het aantal hits sinds aanvang meting weergegeven. Het php-script zonder afbeelding doet hetzelfde, maar dan als tekst.</li>

<li><A name="magnetindex"></A><strong>Magneetindex</strong>: De <code>magneetindex</code> toont hoeveel verkeer een bepaalde pagina aantrekt. Bijvoorbeeld, pagina's met een <code>magneetindex</code> van 1, 2 of 3 zijn 'eerste' pagina's die respectievelijk 10, 100 en 1000 bezoekers per dag aantrekken. Verwar deze index niet met het aantal hits op de betreffende pagina: alle bekeken pagina's door bezoekers die begonnen op deze pagina worden geteld.</li>

<li><A name="bouncerate"></A><strong>Afstootwaarde</strong>: De <code>afstootwaarde</code> is een belangrijk cijfer, want het geeft weer hoeveel procent van de bezoekers vertrekken na het zien van deze pagina. Een pagina met een hoge <code>afstootwaarde</code> (90%) is bijvoorbeeld slecht ontworpen of trekt bezoekers die niet vinden wat ze op grond van zoekmachineresultaten hadden verwacht.</li>

<li><A name="sereferrers"></A><strong>Zoekmachines zijn referers</strong>: Als deze optie geselecteerd is, worden zoekmachineverwijzingen geteld als referers. Het voordeel is dat de volledige URL's van zoekacties die leiden naar de site bewaard blijven. Nadeel: de referertabel kan enorm groot worden. Met beleid gebruiken dus en alleen als er genoeg opslagruimte is.</li>

<li><A name="visitcutoff"></A><strong>Bezoekduur van unieke bezoeker</strong>: Deze optie definieert de duur van een bezoek van een unieke bezoeker in minuten. Elk bezoek van dezelfde bezoeker binnen deze tijdsperiode zal gezien worden als één enkel bezoek, zolang twee opeenvolgende hits de opgegeven limiet niet overschrijden. Standaardwaarde is 15 minuten.</li>

<li><A name="timediff"></A><strong>Tijdverschil</strong>: Als de webserver zich in een andere tijdzone bevindt, kun je met deze optie het tijdverschil in uren aangeven.</li>

<li><A name="URLTrimFactor"></A><strong>URL-inkortfactor</strong>: Met deze optie bepaal je de lengte van de ingekorte URL's en strings op alle phpTrafficA-pagina's. De standaardwaarde is 10. Als je een waarde van 20 invult, worden ingekorte URLs en strings twee keer zo lang. Bij een waarde van 5 de helft van de standaardlengte.</li>

<li><A name="referrerNewDuration"></A><strong>Time to keep referrer marked as new</strong>: new addresses in the referrer page will be marked as <code>new</code> until you click on the link. Referrers older that this setting will not be marked as new, even if you did not click on the link.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatic cleanup of referrer, keyword, IP list, and path tables</strong>: if you choose this option, tables with referrers, keywords, IP addresses, and paths will be automatically cleaned regularly. This will remove entries older than 2 months and that have been used only once.</li>

<li><A name="autoCleanAccess"></A><strong>Automatic cleanup of access tables</strong>: if you choose this option, access tables (page counts and unique visitors) will be automatically cleaned regularly. This will remove data older than two months. The total number of acces to each page and the statistics for the whole site will be preserved, but all acces data to individual pages older that two months will be lost.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>