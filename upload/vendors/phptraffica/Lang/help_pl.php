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
<title>phpTrafficA. Pomoc</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Zamknij okno</a></div>
<h1>phpTrafficA. Pomoc</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Czarna lista</strong>: ten formularz służy do dodawania do czarnej listy błędnych linków polecających. Na przykład, niektóre z wyszukiwarek nie zezwalają na wyodrębnianie słów kluczowych używanych w czasie dostępu do strony, jednakże nie mogą i nie powinny być zaliczane do linków polecających. Ta funkcja jest także używana do blokowania stron takich, jak opisane na <a href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referer spam</a></li>

<li><A name="domain"></A><strong>Domena</strong>: podaj nazwę domeny, którą chcesz śledzić. Coś w stylu <code>mojserwer.net/mojkatalog/</code></li>

<li><A name="savehost"></A><strong>Najnowsze hosty</strong>: phpTrafficA utrzymuje tabele z pełnymi informacjami o ostatnich odwiedzających. Po pewnym czasie pełne informacje są po prostu usuwane w celu zachowania miejsca, przechowywane są  natomiast informacje w postaci przetworzonej. Dane z tabeli używane są w zakładce <code>Ostatnie wizyty</code> oraz podczas przetwarzania danych w <code>Analizach ścieżki</code>. Jeśli chcesz mieć więcej hostów włączonych do tych funkcji, zwiększ liczbę zachowanych hostów. phpTrafficA może stać się bardzo powolnym programem gdy tabele będą zbyt duże.</li>

<li><A name="oslist"></A><strong>Lista systemów operacyjnych</strong>: lista systemów operacyjnych. Ta tabela przedstawia listę systemów operacyjnych, oraz sposób ich rozpoznawania podczas identyfikacji przeglądarki.Każda linia zawiera ciąg znaków identyfikacyjnych kolejnych nazw systemów operacyjnych, oddzielone symbolem <code>|</code>. Ta tabela jest zazwyczaj uaktualniana wraz z nowymi wersjami phpTrafficA.</li>

<li><A name="public"></A><strong>Publiczna</strong>: wszyscy odwiedzający mogą oglądać statystyki jeśli wybierzesz dla domeny opcję <code>public</code>. Statystyki dla domen z opcją <code>private</code> są dostępne tylko jeśli się zalogujesz.</li>

<li><A name="selist"></A><strong>Lista wyszukiwarek</strong>: ta tabela wyświetla listę wyszukiwarek, jak je rozpoznawać, i jak uzyskać słowa kluczowe z ciągu znaków linku polecającego. Każda linia zawiera informację dla jednej wyszukiwarki, oddzielonej za pomocą symbolu <code>|</code>. pierwszy element to nazwa, drugi to ciąg znaków używany do rozpoznawania wyszukiwarki w linku polecającym i trzeci jest zmienną, którą wyszukiwarka używa do przekazywania słów kluczowych (oddzielonych symbolem <code>:</code> dla wielu możliwości). Na przykład wyszukiwanie w google będzie rozpoznane po linku <code>google.com</code> i zmiennej <code>q</code>. Ta tabela jest uaktualniana wraz z nowymi wersjami phpTrafficA.</li>

<li><A name="table"></A><strong>Tabela</strong>: nazwa bazy dla tabel sql. Utworzymy kilka tabel dla każdej ze stron śledzonych przez phpTrafficA. Ich nazwy zawsze będą zaczynać się od nazwy podanej tutaj.</li>

<li><A name="trim"></A><strong>Skróć URL</strong>: jeśli ta opcja jest ustawiona na <code>True</code>, URL w statystykach będzie skrócony. Na przykład <code>index.php?lang=pl</code> i <code>index.php?lang=en</code> będzie przechowywany jako <code>index.php</code>. To standardowe zachowanie w phpTrafficA. Bądź ostrożny jeśli zdecydujesz się na zachowanie pełnych nazw URL dla swoich statystyk, bo liczba kombinacji nazw URL może być nieograniczona dla w pełni dynamicznych stron. <strong> Zmiana tej opcji nie jest polecana, chyba że będziesz używał phpTrafficA przez krótki okres czasu</strong>.</li>

<li><A name="wblist"></A><strong>Lista przeglądarek</strong>: ta tabela wyświetla listę przeglądarek i sposób w jaki je rozpoznaje w ciągu znaków jakimi przedstawia się przeglądarka. Każda linia zawiera ciąg znaków identyfikacyjnych poprzedzonych nazwą przeglądarki, oddzielonych za pomocą symbolu <code>|</code>. Ta tabela jest uaktualniana wraz z nowymi wersjami phpTrafficA.</li>

<li><A name="countbots"></A><strong>Licz boty</strong>: jeśli zaznaczysz tę opcję, roboty odwiedzające Twoją stronę (googlebot, yahoo slurp i inne), będą liczone jako zwykli odwiedzający. Jeżeli nie nie wybierzesz tej opcji, ni będą one uwzględniane w statystykach. Zobaczysz je w tabeli najnowszych hostów, ale tylko tam.</li>

<li><A name="counter"></A><strong>Licznik</strong>: jeśli wybierzesz tę opcję, phpTrafficA będzie działać jak licznik. Jeśli wybrałeś któryś ze skryptów zliczających statystyki przy pomocy obrazka, to obrazek ten będzie zawierał liczbę odsłon od momentu rozpoczęcia zbierania danych. Jeśli wybrałeś metodę "czystego" php do zbierania danych statystycznych (bez obrazka), phpTrafficA będzie wyświetlać liczbę wszystkich odsłon aktualnej strony.</li>

<li><A name="magnetindex"></A><strong>Indeks przyciągania</strong>: <code>Indeks przyciągania</code> jest użytecznym narzędziem, które mierzy ja dużo wizyt uzyskujesz dzięki danej stronie. Na przykład, strony z <code>Indeksem przyciągania</code> 1, 2 i 3 są strony powodujące odpowiednio 10, 100 i 1000 odsłon dziennie. Nie należy mylić tego współczynnika ze średnią liczbą wejść na daną stronę. Liczymy wszystkie wizyty rozpoczynające się od danej strony, nie tylko wizyty na nią.</li>

<li><A name="bouncerate"></A><strong>Wskaźnik odbijania</strong>: <code>wskaźnik odbijania</code> to ważna informacja, jako że mówi o procencie ludzi, którzy 'odbili się' (opuścili nasz serwis) po obejrzeniu tylko tej jednej strony.</li>

<li><A name="sereferrers"></A><strong>Uznaj wyszukiwarki jako linki polecające</strong>: Jeśli wybrałeś tę opcję, zapytania wyszukiwarki będą wyświetlane w tabeli linków polecających. To pozwoli Ci posiadać pełny URL wszystkich wyszukiwań prowadzących do Twojej strony. Z drugiej strony Twoja tabela linków polecających może przybrać bardzo duże rozmiary, zatem używaj tej opcji z rozwagą, i tylko jeśli masz dużo wolnego miejsca na dysku twardym.</li>

<li><A name="visitcutoff"></A><strong>Zakończenie unikalnej wizyty (w minutach)</strong>: Ta opcja ustawia zakończenie unikalnej wizyty (w minutach). Jeśli unikalny odwiedzający (bazując na adresie IP) nie będzie aktywny przez ten czas, następna odsłona z tego samego adresu IP będzie potraktowana jako nowa unikalna wizyta. Standardowa wartość to 15 minut.</li>

<li><A name="timediff"></A><strong>Time difference</strong>: use this option if your server is not in the same timezone that your website. Set the time difference, in hours.</li>

<li><A name="URLTrimFactor"></A><strong>URL trim factor</strong>: use this option to set the length of trimmed strings and URL in various phpTrafficA pages. Default value is 10. If you choose a value of 20, trimmed strings and URL will be twice longer than default. If you choose a value of 5, trimmed strings and URL will be twice shorter than default.</li>

<li><A name="referrerNewDuration"></A><strong>Time to keep referrer marked as new</strong>: new addresses in the referrer page will be marked as <code>new</code> until you click on the link. Referrers older that this setting will not be marked as new, even if you did not click on the link.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatic cleanup of referrer, keyword, IP list, and path tables</strong>: if you choose this option, tables with referrers, keywords, IP addresses, and paths will be automatically cleaned regularly. This will remove entries older than 2 months and that have been used only once.</li>

<li><A name="autoCleanAccess"></A><strong>Automatic cleanup of access tables</strong>: if you choose this option, access tables (page counts and unique visitors) will be automatically cleaned regularly. This will remove data older than two months. The total number of acces to each page and the statistics for the whole site will be preserved, but all acces data to individual pages older that two months will be lost.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>