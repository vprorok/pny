<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Dansk oversættelse ved Lars J. Helbo, http://www.salldata.dk/

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
<title>phpTrafficA hjælp</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Fenster schlie&szlig;en</a></div>
<h1>phpTrafficA hjælp</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Sort liste</strong>: Denne formular bruges til at fjerne fejlagtige henvisere (referrer), f.eks.: Der findes søgemaskiner, som ikke tillader, at de anvendte søgebegreber udlæses. De kan så heller ikke vises i søgemaskinestatistikken, og de kan og må ikke vises som henvisere. Funktionen bruges også til at udelukke sider, der bruger <A href="http://en.wikipedia.org/wiki/Referrer_spam" target="_blank">Referrer Spam</A>.</li>

<li><A name="domain"></A><strong>Domæne</strong>: Her angives de domænenavne, som skal overvåges, f.eks.: <code>minserver.dk/minfolder/</code>.</li>

<li><A name="savehost"></A><strong>Seneste gæst</strong>: phpTrafficA gemmer en liste med alle informationer om de seneste gæster. Den slettes efter en vis tid, for at spare lagerplads. Men dataene bevares i forarbejdet form. Informationerne anvendes i <code>stianalysen</code> 
som <code>sidste gæst</code>. Hvis du vil bevare flere informationer, forhøjer du simpelthen antallet af gemte gæster. phpTrafficA kan blive langsomt, 
hvis antallet af gemte gæster er stort.</li>

<li><A name="oslist"></A><strong>OS liste</strong>: Liste over operativsystemer. Tabellen indeholder en liste med operativsystemer og deres kendetegn til browseridentifikationen. Hver linje indeholder en identifikationstekst fulgt af operativsystemnavnet. De to værdier adskilles af <code>|</code>. Denne tabel udvides ved opgraderinger af phpTrafficA.</li>

<li><A name="public"></A><strong>Offentlig</strong>: Adgang til <code>Offentlige</code> domæner er tilladt for alle gæster. Adgang til <code>Private</code> domæner kun med login.</li>

<li><A name="selist"></A><strong>Søgemaskineliste</strong>: Tabellen indeholder en liste med søgemaskiner, deres kendetegn og 
	oplysninger om udlæsning af søgebegreber. Hver linje indeholder informationer om en søgemaskine. Informationerne adskilles af <code>|</code>. Første indførsel indeholder navnet, den anden er en tekst, til identifikation af søgemaskinen, den tredje  indeholder navnet på den variable, hvori søgebegrebet overføres (flere muligheder adskilles af <code>:</code>). Eksempel: Søgemaskinen Google kendes på teksten <code>google.com</code>, den variable <code>q</code> angiver søgebegrebet  =&gt; <code>Google|google.com|q</code>. Denne tabel udvides ved opgraderinger af phpTrafficA.</li>

<li><A name="table"></A><strong>Tabel</strong>: Præfiks til SQL-tabeller. For hver overvåget webside anlægges flere tabeller. Navnet på tabellen begynder med den her anførte tekst.</li>

<li><A name="trim"></A><strong>URL-afskæring</strong>: Hvis denne option sættes til <code>Ja</code>, afskæres side-adresserne i statistikken, f.eks.: <code>index.php?lang=da</code> og <code>index.php?lang=en</code> gemmes som <code>index.php</code>. Det er standard for  phpTrafficA. Vær forsigtig med at gemme hele adressen. Det kan føre til mange indførsler med de forskellige kombinationer ved dynamisk websider. <strong>Det kan ikke anbefales, at ændre denne værdi, når phpTrafficA har kørt i en periode.</strong></li>

<li><A name="wblist"></A><strong>Webbrowserliste</strong>: Liste med de forskellige webbrowsere. Hver linje indeholder en identifikationstekst fugt af browsernavnet. De to værdier adskilles af <code>|</code>. Denne tabel udvides ved opgradering af phpTrafficA.</li>

<li><A name="countbots"></A><strong>Tæl bots</strong>: Når denne option er valgt, tælles søgemaskiner (Googlebot, Yahoo Slurp, etc.) som normale gæster. Hvis denne option ikke vælges, indgår de ikke i statistikken. De vises kun i listen med de seneste gæster.</li>

<li><A name="counter"></A><strong>Tæller</strong>: Hvis denne option vælges, fungerer phpTrafficA også som tæller. Hvis du vælger et billedscript til registreringen, vil billedet vise antallet af gæster siden optegnelsens start. Hvis du vælger en ren PHP-løsning til tælling af træffere (intet billede), vil phpTrafficA vise antallet af træffere for siden (som tekst).</li>

<li><A name="magnetindex"></A><strong>Magnet-indeks</strong>: <code>Magnet-indeks</code> er et nyttigt værktøj. Det fortæller, hvor hyppigt gæster starter på denne side, f.eks. er sider med et <code>Magnet-indeks</code> på 1, 2 og 3 indgangssider med 10, 100, 1000 direkte kald pr. dag. Dette tal må ikke forveksles med antal kald af en side. Et kald tælles her kun med, hvis det er gæstens første side.</li>

<li><A name="bouncerate"></A><strong>Afstødningsrate</strong>: <code>Afstødningsraten</code> er et mål for, hvor mange gæster, der forlader websiden, efter at have set denne side.</li>

<li><A name="sereferrers"></A><strong>Søgemaskine er henviser</strong>: Hvis denne option er valgt, opføres søgemaskineforespørgsler i henvisningslisten. Det giver fuld kontrol over de søgninger, som førte hen til hjemmesiden. Hermed kan henvisningstabellen dog blive meget stor. Vær forsigtig med denne option, hvis du kun har lidt lagerplads.</li>

<li><A name="visitcutoff"></A><strong>Besøgsende</strong>: Denne option beskriver afslutningen af besøget i minutter. Når en enkelt gæst (baseret på IP-adressen) er inaktiv i længere tid end her angivet, og så igen kalder en side, så betragtes det som en ny gæst. Standardindstillingen er 15 minutter.</li>

<li><A name="timediff"></A><strong>Tidsforskel</strong>: Brug denne option, hvis serveren ikke er i samme tidszone som hjemmesiden. Her angives tidsforskellen i timer.</li>

<li><A name="URLTrimFactor"></A><strong>URL-afskærings faktor</strong>: Denne option bruges til at forkorte tekststrenge i URLs. Standardværdien er 10, med en værdi på 20 bliver URLs dobbelt så lange, med en værdi på 5 bliver de kun halvt så lange.</li>

<li><A name="referrerNewDuration"></A><strong>Tid hvori henvisere markeres som nye</strong>: nye adresser i henviserlisten markeres som <code>new</code> indtil du klikker på linket. Henvisere, som er ældre end denne indstilling markeres ikke som nye, selv hvis du ikke har klikket på linket.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatisk oprydning af henvisere, nøgleord, IP-liste og forløbstabeller</strong>: hvis du vælger denne option, vil der regelmæssigt og automatisk blive ryddet op i lister med henvisere, nøgleord, IP-adresser og forløb. Det vil fjerne indførsler, der er ældre end to måneder og kun har været brugt en gang.</li>

<li><A name="autoCleanAccess"></A><strong>Automatisk oprydning i adgangstabeller</strong>: hvis du vælger denne option, vil der regelmæssigt og automatisk blive ryddet op i adgangstabeller (sidekald og unikke besøgende). Det vil fjerne data, der er ældre end to måneder. Det samlede antal kald af hver side og statistikken for hele websiden vil blive bevaret, men adgangsdata til enkelte sider ældre end to måneder vil være tabt.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>