<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)

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
<title>phpTrafficA - Hjälpen</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">St&auml;ng f&ouml;nster</a></div>
<h1>phpTrafficA - Hjälpen</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Svartlista</strong>: detta formul&auml;r anv&auml;nds f&ouml;r att svartlista skadliga h&auml;nvisningsadresser (referrers). Till exempel, det finns ett f&aring; antal s&ouml;kmotorer som inte till&aring;ter utdrag av s&ouml;korden som anv&auml;nds f&ouml;r att komma till hemsidan, de kan inte inkluderas i s&ouml;kmotor statistiken, men de kommer inte och borde inte r&auml;knas som h&auml;nvisningsadresser. Denna funktion anv&auml;nds ocks&aring; till att banlysa sidor som utf&ouml;r s.k <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referrer spam</A>.</li>

<li><A name="domain"></A><strong>Dom&auml;n</strong>: skriv in rooten av dom&auml;nen du vill h&aring;lla koll p&aring;, exempel <code>minserver.net/minmapp/</code></li>

<li><A name="savehost"></A><strong>Senaste v&auml;rdar</strong>: phpTrafficA h&aring;ller en tabell med den hela informationen av de senaste bes&ouml;karna. Efter en tid s&aring; tas de helt enkelt bort f&ouml;r att spara plats och informationen sparas i en bearbetad form. Denna tabell anv&auml;nds i <code>Senaste Bes&ouml;karna</code> fliken och datan som &auml;r bearbetad i <code>S&ouml;kv&auml;gs Analys</code> under <code>Navigation</code> fliken. Om du vill ha flera v&auml;rdar inkluderade i denna funktion, &ouml;ka antalet av sparade v&auml;rdar. phpTrafficA kan bli v&auml;ldigt l&aring;ngsam med stora tabeller.</li>

<li><A name="oslist"></A><strong>Operativsystem lista</strong>: Denna tabell visar listan med operativsystem och hur de uppt&auml;cks i webl&auml;sar identifikationen. Varje rad inneh&aring;ller en identifikations str&auml;ng f&ouml;ljt av operativsystem namnet, separerad med symbolen <code>|</code>. Denna tabell uppdateras vanligen med phpTrafficA uppgraderingar.</li>

<li><A name="public"></A><strong>Offentlig</strong>: alla bes&ouml;kare till&aring;ts titta p&aring; statistiken relaterad till <code>offentliga</code> dom&auml;ner. Statistik f&ouml;r <code>privata</code> dom&auml;ner &auml;r endast tillg&auml;nglig n&auml;r du loggar in.</li>

<li><A name="selist"></A><strong>S&ouml;kmotor lista</strong>: denna tabell visar en lista med s&ouml;kmotorer och hur de uppt&auml;cks, och hur man extraherar s&ouml;kord i h&auml;nvisningsadress str&auml;ngen. Varje rad inneh&aring;ller information f&ouml;r en s&ouml;kmotor, separerad med symbolen <code>|</code>. Den f&ouml;rsta posten &auml;r namnet, andra posten &auml;r en str&auml;ng som anv&auml;nds f&ouml;r att uppt&auml;cka s&ouml;kmotorn i h&auml;nvisningsadress URL:en, och den tredje posten &auml;r variabeln denna s&ouml;kmotor anv&auml;nds till att passera s&ouml;kord (separerade av symbolen <code>:</code> f&ouml;r flera m&ouml;jligheter). Till exempel, en s&ouml;kning i google kommer att uppt&auml;ckas med URL:en <code>google.com</code> och variabeln <code>q</code>. Denna tabell uppdateras vanligen med phpTrafficA uppgraderingar.</li>

<li><A name="table"></A><strong>Tabell</strong>: basnamnet för sql tabellen. Vi skapar flera tabeller för varje hemsida som phpTrafficA registrerar. Deras namn startar alltid med strängen som tillhandahålls här.</li>

<li><A name="trim"></A><strong>Trimma URL</strong>: om denna st&auml;lls in p&aring; <code>true</code>, URL som anv&auml;nds i statistiken kommer att trimmas. Till exemel, <code>index.php?lang=fr</code> och <code>index.php?lang=en</code> kommer bli sparade som <code>index.php</code>. Detta &auml;r standard beteende i phpTrafficA. Var aktsam om du v&auml;ljer att beh&aring;lla hela URL:en till din statistik d&aring; antalet kombinationer av URL:er kan bli ofantliga f&ouml;r helt dynamiska hemsidor. F&ouml;r&ouml;vrigt, <strong>s&aring; &auml;r det inte rekommenderat att &auml;ndra denna parameter n&auml;r du anv&auml;nt phpTrafficA en tid</strong>.</li>

<li><A name="wblist"></A><strong>Webl&auml;sar lista</strong>: lista med webl&auml;sare. Denna tabell visar en lista med webl&auml;sare och hur de uppt&auml;cks i webl&auml;sar identfikationen. Varje rad inneh&aring;ller en identifikations str&auml;ng f&ouml;ljt av webl&auml;sarnamnet, separerade med symbolen <code>|</code>. Denna tabell uppdateras vanligen med phpTrafficA uppgraderingar.</li>

<li><A name="countbots"></A><strong>R&auml;kna robotar</strong>: om du v&auml;ljer detta val s&aring; kommer robotar som bes&ouml;ker din sida (googlebot, yahoo slurp och s&aring;dana) kommer att r&auml;knas som vanliga bes&ouml;kare. Om du inte v&auml;ljer detta val, s&aring; kommer de inte inkluderas i statistiken. Du kommer se dem i senaste v&auml;rdar tabellen men det &auml;r ocks&aring; allt.</li>

<li><A name="counter"></A><strong>R&auml;knare</strong>: om du v&auml;ljer detta val s&aring; kommer phpTrafficA ocks&aring; fungera som en r&auml;knare. Om du valde ett av bild scripten f&ouml;r att registrera din statistik, s&aring; kommer bilden inneh&aring;lla antalet tr&auml;ffar sedan du b&ouml;rjade h&aring;lla koll p&aring; din sida. Om du valde en ren php teknik f&ouml;r att registrera din statistik (ingen bild), s&aring; kommer phpTrafficA visa det totala antalet av tr&auml;ffar f&ouml;r den g&auml;llande sidan.</li>

<li><A name="magnetindex"></A><strong>Magnet Index</strong>: <code>Magnet index</code> &auml;r ett anv&auml;ndbart verktyg d&aring; det m&auml;ter hur mycket trafik som f&ouml;rts till din sida fr&aring;n en viss sida. Till exempel, sidor med ett <code>magnet index</code> av 1, 2, och 3 &auml;r ing&aring;ngssidor f&ouml;r 10, 100, 1000 tr&auml;ffar per dag, respektivt. F&ouml;rv&auml;xla inte denna faktor med det genomsnittliga antalet tr&auml;ffar p&aring; en viss sida. Vi r&auml;knar alla tr&auml;ffar fr&aring;n bes&ouml;k som startar p&aring; denna sida, inte bara bes&ouml;k till denna sida.</li>

<li><A name="bouncerate"></A><strong>Studsv&auml;rde</strong>: Eller <code>bounce rate</code> som det kallas, är ett viktigt värde då det talar om för dig procenttalet av personer som "studsar" iväg (lämnar) från din sida efter tittat endast på denna sida.</li>

<li><A name="sereferrers"></A><strong>S&ouml;kmotorer &auml;r h&auml;nvisningsadresser</strong>: Om du v&auml;ljer detta val s&aring; kommer s&ouml;kmotorer listas ocks&aring; i h&auml;nvisningsadress tabellen. Detta kommer att till&aring;ta dig att ha hela URL:en av alla s&ouml;kningar som leder till din hemsida. D&auml;remot s&aring; kommer din h&auml;nvisningsadress tabell bli mycket stor, s&aring; anv&auml;nd detta val med f&ouml;rsiktighet, och endast om du har mycket serverplats!</li>

<li><A name="visitcutoff"></A><strong>Auto bes&ouml;ksavst&auml;ngningstid</strong>: Detta val kommer st&auml;lla in automatiska avst&auml;ngningstiden  f&ouml;r bes&ouml;kare i minuter. Om en unik bes&ouml;kare (baserad p&aring; IP adresser) inte har varit aktiva i mer &auml;n denna avst&auml;ngningstid, s&aring; kommer n&auml;sta tr&auml;ff fr&aring;n samma IP adress hanteras som ett nytt unikt bes&ouml;k. Standardv&auml;rde &auml;r 15 minuter.</li>

<li><A name="timediff"></A><strong>Tidsskillnad</strong>: anv&auml;nd detta val om din server inte &auml;r i samma tidszon som dun hemsida. St&auml;ll in tidsskillnaden i timmar.</li>

<li><A name="URLTrimFactor"></A><strong>URL trimnings faktor</strong>: anv&auml;nd detta val f&ouml;r att st&auml;lla in l&auml;ngden p&aring; trimmade str&auml;ngar och URL:er i olika phpTrafficA sidor. Standard v&auml;rdet &auml;r 10. Om du v&auml;ljer v&auml;rdet 20 s&aring; kommer trimmade str&auml;ngar och URL:er bli dubbelt s&aring; l&aring;nga som standardv&auml;rdet. Om du v&auml;ljer v&auml;rdet 5 s&aring; kommer trimmade str&auml;ngar och URL:er bli h&auml;lften av standardv&auml;rdet.</li>

<li><A name="referrerNewDuration"></A><strong>Tid att behålla hänvisningsadresser markerat som ny</strong>: nya adresser på sidan för hänvisningsadresser kommer att vara markerad som <code>ny</code> tills du klickar på länken. Hänvisningsadresser äldre än denna inställning kommer inte markeras som ny, även om du inte har klickat på länken.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatisk rensning av hänvisnings adresser, nyckelord, IP listan och sökvägs tabeller.</strong>: om du markerar detta val så kommer tabeller med hänvisningsadresser, nyckelord, IP adresser, och sökvägar att regelbundet rensas automatiskt. Detta kommer att ta bort noteringar som är äldre än 2 månader och som har använts bara en gång.</li>

<li><A name="autoCleanAccess"></A><strong>Automatisk rensning av åtkomst tabeller.</strong>: om du väljer detta val så kommer åtkomst tabellerna (sidvisningar och unika besökare) att automatiskt rensas regelbundet. Detta kommer att ta bort data som är äldre än två månader gamla. Det totala antalet åtkomster till varje sida och statistiken för hela hemsidan kommer att sparas, men all data till individuella sidor äldre än två månader kommer att försvinna.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>