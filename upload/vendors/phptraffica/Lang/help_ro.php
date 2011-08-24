<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Romanian translation by Constantin Iancu, http://www.counterzone.ro/

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
<title>phpTrafficA - Fereastr&#259; de ajutor</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">&#206;nchide fereastra</a></div>
<h1>phpTrafficA - Fereastr&#259; de ajutor</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Lista neagr&#259;</strong>: aceast&#259; denumire se folose&#351;te pentru referen&#355;i incorec&#355;i. Spre exemplu, sunt c&#226;teva motoare de c&#259;utare care nu permit extragerea cuvintelor cheie folosite pentru accesarea site-ului &#351;i care nu pot fi incluse &#238;n statisticile motoarelor de c&#259;utare, dar acestea nu sunt &#351;i nu trebuiesc contorizate ca referen&#355;i. Aceast&#259; func&#355;ie este folosit&#259; &#351;i pentru a bloca accesul site-urilor care v&#259; trimit 
<A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">mesaje nedorite f&#259;r&#259; acordul dvs</A>.</li>

<li><A name="domain"></A><strong>Domeniul</strong>: Introduce&#355;i "root-ul" domeniului pe care dori&#355;i s&#259;-l monitoriza&#355;i, spre exemplu <code>serverul_meu/directorulmeu/</code>. Dac&#259; ave&#355;i mai mult site-uri pe acela&#351;i IP (IP based Virtual Host) introduce&#355;i numele site-ului complet, spre exemplu <code>www.siteulmeu.ro</code>.</li>

<li><A name="savehost"></A><strong>Ultimii vizitatori</strong>: phpTrafficA p&#259;streaz&#259; un tabel cu toate informa&#355;iile despre ultimii vizitatori. Dup&#259; un anumit timp, acesta este &#351;ters pentru a salva spa&#355;iul de pe disc, iar informa&#355;iile sunt p&#259;strate &#238;ntr-un formular. Acest tabel este folosit &#238;n c&#226;mpul <code>Ultimile vizite</code> pentru procesarea datelor din <code>Analizele rutei</code>. Dac&#259; dori&#355;i s&#259; ave&#355;i mai mul&#355;i vizitatori inclu&#351;i &#238;n aceste func&#355;ii, mari&#355;i num&#259;rul vizitatorilor salva&#355;i. phpTrafficA poate deveni extrem de lent &#238;n afi&#351;area statisticilor dac&#259; tabelele sunt mari.</li>

<li><A name="oslist"></A><strong>Lista sistemelor de operare</strong>: cuprinde sistemele de operare cunoscute. Acest tabel v&#259; arat&#259; lista sistemelor de operare &#351;i cum sunt ele indentificate de c&#259;tre navigatorul dvs. de internet. Fiecare linie con&#355;ine un &#351;ir de caractere de identificare urmat de numele sistemului de operare, separate de simbolul <code>|</code>. Uzual, acest tabel se actualizeaz&#259; automat c&#226;nd actualiza&#355;i versiunea programului phpTrafficA.</li>

<li><A name="public"></A><strong>Public&#259;</strong>: to&#355;i vizitatorii pot vedea statisticile domeniilor <code>publice</code>. Domeniile cu statistici <code>private</code> sunt disponibile numai dup&#259; autentificare.</li>

<li><A name="selist"></A><strong>Lista motoarelor de c&#259;utare</strong>: acest tabel v&#259; afi&#351;eaz&#259; lista motoarelor de c&#259;utare, cum sunt ele detectate &#351;i cum se extrag cuvintele cheie din &#351;irul de caractere al referentului. Fiecare linie con&#355;ine informa&#355;ii pentru un singur motor de c&#259;utare, separate de simbolul <code>|</code>. Prima informa&#355;ie este numele, a doua este &#351;irul de caractere folosit pentru a detecta motorul de c&#259;utare din adresa URL a referentului &#351;i a treia este variabila
pe care motorul de c&#259;utare o folose&#351;te cand analizeaz&#259; cuvintele cheie (separate de simbolul <code>:</code> c&#226;nd exist&#259; mai multe posibilit&#259;&#355;i). Spre exemplu, o c&#259;utare &#238;n google va fi detectat&#259; cu adresa URL <code>google.com</code> &#351;i variabila <code>q</code>. Acest tabel se actualizeaz&#259; cand actualiza&#355;i versiunea programului phpTrafficA.</li>

<li><A name="table"></A><strong>Tabel</strong>: numele de baz&#259; pentru tabele &#238;n sql. Se vor creea mai multe tabele pentru fiecare site monitorizat de c&#259;tre phpTrafficA. Numele acestor tabele va &#238;ncepe cu &#351;irul de caractere introdus de dvs.</li>

<li><A name="trim"></A><strong>Taie URL</strong>: dac&#259; alege&#355;i <code>Da</code>, adresele URL utilizate pentru statistici vor fi t&#259;iate. Spre exemplu, <code>index.php?lang=ro</code> &#351;i <code>index.php?lang=en</code> vor fi &#238;nregistrate ca <code>index.php</code>. Acesta este op&#355;iunea implicit aleas&#259; &#238;n phpTrafficA. Fi&#355;i atent dac&#259; v&#259; decide&#355;i s&#259; p&#259;stra&#355;i &#238;ntreaga adres&#259; URL pentru statisticile dvs., deoarece un num&#259;r mare de combina&#355;ii a adreselor URL pot fi imense pentru site-urile de internet foarte dinamice. Mai mult, <b>nu este recomandat s&#259; schimba&#355;i acest parametru odat&#259; ce ve&#355;i folosi phpTrafficA pentru perioade scurte.</li>

<li><A name="wblist"></A><strong>Lista navigatoarelor de internet</strong>: este lista web browser-elor (programelor folosite pentru a naviga pe internet). Acest tabel con&#355;ine lista navigatoarelor de internet &#351;i cum sunt ele identificate. Fiecare linie con&#355;ine un &#351;ir de caractere de indentificare urmat de numele navigatorului, separate de simbolul <code>|</code>. Acest tabel se actualizeaz&#259; cand actualiza&#355;i versiunea programului phpTrafficA.</li>

<li><A name="countbots"></A><strong>Contor robo&#355;i</strong>: dac&#259; selecta&#355;i aceast&#259; op&#355;iune robo&#355;ii care v&#259; viziteaz&#259; site-ul (robotul google, yahoo &#351;i al&#355;ii) vor fi contoriza&#355;i ca vizitatori normali. Dac&#259; nu selecta&#355;i aceast&#259; op&#355;iune, robo&#355;ii nu vor fi inclu&#351;i &#238;n statistici. &#206;i ve&#355;i vedea &#238;n tabelul ultimilor vizitatori, dar nu vor fi lua&#355;i &#238;n calcul pentru statistici.</li>

<li><A name="counter"></A><strong>Contor</strong>: dac&#259; selecta&#355;i aceast&#259; op&#355;iune, phpTrafficA va avea &#351;i func&#355;ie de contor. Cand selecta&#355;i unul din scripturile pentru &#238;nregistrarea statisticilor care con&#355;in imagine , imaginea va afi&#351;a num&#259;rul dvs. de vizualiz&#259;ri, &#238;nc&#259; de la &#238;nceputul monitorizarii site-ului. Dac&#259; selecta&#355;i scriptul f&#259;r&#259; imagine &#238;n cod php, phpTrafficA va afi&#351;a num&#259;rul total de vizualiz&#259;ri ale paginii curente.</li>

<li><A name="magnetindex"></A><strong>Index atr&#259;g&#259;tor</strong>: parametrul <code>index atr&#259;g&#259;tor</code> este o ustensil&#259; puternic&#259; care m&#259;soar&#259; c&#226;t de mult trafic este adus pe site-ul dvs. de o anumit&#259; pagin&#259;. Spre exemplu, paginile cu un <code>index atr&#259;g&#259;tor</code> de 1, 2 si 3 sunt pagini de intrare pentru 10, 100, 1000 vizite pe zi. Nu confunda&#355;i acest factor cu media vizitelor date de o pagin&#259;. Se contorizeaz&#259; toate vizualiz&#259;rile care pleac&#259; de la aceast&#259; pagin&#259;, nu numai vizualiz&#259;rile acestei pagini.</li>

<li><A name="bouncerate"></A><strong>Rata de cre&#351;tere</strong>: parametrul <code>rata de cre&#351;tere</code> este un parametru important care v&#259; indic&#259; procentual num&#259;rul de vizitatori din num&#259;rul total al vizitatorilor, care au accesat numai aceast&#259; pagin&#259;, respectiv numai vizitatorii care au accesat doar pagina aceasta &#351;i apoi au p&#259;r&#259;sit site-ul.</li>

<li><A name="sereferrers"></A><strong>Motoarele de c&#259;utare sunt referen&#355;i</strong>: d&#259;c&#259; selecta&#355;i aceast&#259; op&#355;iune, motoarele de c&#259;utare care v&#259; indexeaz&#259; site-ul vor fi considerate referen&#355;i. Aceasta v&#259; permite s&#259; ave&#355;i &#238;ntreaga adres&#259; URL pentru toate c&#259;utarile site-ului dvs. pe motoarele de c&#259;utare. Pe de alt&#259; parte, tabelul referen&#355;ilor poate deveni mare, deci ave&#355;i grij&#259; cum alege&#355;i aceast&#259; op&#355;iune &#351;i activa&#355;i-o doar dac&#259; ave&#355;i o gramad&#259; de spa&#355;iu liber pentru baza de date.</li>

<li><A name="visitcutoff"></A><strong>Sfar&#351;itul vizitei pentru un vizitator unic</strong>: aceast&#259; op&#355;iune determin&#259; timpul maxim care se &#238;nregistreaz&#259; atunci c&#226;nd un vizitator v&#259; acceseaz&#259; pagina. Dac&#259; un vizitator unic (determinat de IP-ul lui) nu este activ pentru mai mult timp dec&#226;t acesta, urm&#259;toarea vizit&#259; de la aceea&#351;i adres&#259; IP va fi tratat&#259; ca o nou&#259; vizit&#259; unic&#259;. Valoarea implicit&#259; este de 15 minute.</li>

<li><A name="timediff"></A><strong>Decalaj orar</strong>: utilizaţi această opţiune dacă serverul dvs. nu este pe acelaşi fus orar cu site-ul web. Introduceţi diferenţa de fus orar in ore.</li>

<li><A name="URLTrimFactor"></A><strong>Lungimea adresei URL</strong>: utilizaţi această opţiune pentru a stabili lungimea adresei URL în diferitele pagini ale phpTrafficA. Valoarea implicită este 10. Dacă alegeţi valoarea 20, lungimea adresei URL va fi de două ori mai lungă decât implicit. Dacă alegeţi valoarea 5, lungimea adresei URL va fi pe jumătate faţă de valoarea implicită.</li>

<li><A name="referrerNewDuration"></A><strong>Time to keep referrer marked as new</strong>: new addresses in the referrer page will be marked as <code>new</code> until you click on the link. Referrers older that this setting will not be marked as new, even if you did not click on the link.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatic cleanup of referrer, keyword, IP list, and path tables</strong>: if you choose this option, tables with referrers, keywords, IP addresses, and paths will be automatically cleaned regularly. This will remove entries older than 2 months and that have been used only once.</li>

<li><A name="autoCleanAccess"></A><strong>Automatic cleanup of access tables</strong>: if you choose this option, access tables (page counts and unique visitors) will be automatically cleaned regularly. This will remove data older than two months. The total number of acces to each page and the statistics for the whole site will be preserved, but all acces data to individual pages older that two months will be lost.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>