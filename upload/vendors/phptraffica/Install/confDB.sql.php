<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/

$browser_list = "Bot|Crawler\\nbot|Crawler\\ncrawler|Crawler\\nCrawler|Crawler\\nOpera Mini|Opera Mini\\nOpera/10|Opera 10\\nOpera/9|Opera 9\\nOpera 9|Opera 9\\nOpera 8|Opera 8\\nOpera/8|Opera 8\\nIEMobile|IEMobile\\nMSIE 8|MSIE 8\\nMSIE 7|MSIE 7\\nMSIE 6|MSIE 6\\nFirefox|Firefox\\nfirefox|Firefox\\nChrome|Chrome\\nSafari|Safari\\nKonqueror/3|Konqueror 3\\nGooglebot|Googlebot\\nMediapartners|Google Adwords\\narchive.org|Crawler\\nmsnbot|Crawler\\nSlurp|Crawler\\nMSIE 5|MSIE 5\\nrobot|Crawler\\nclikserver|Crawler\\nvbseo|Crawler\\nPlayStation Portable|Sony PSP\\nPLAYSTATION|Sony Playstation\\nNokia|Nokia\\nBlackBerry|BlackBerry\\nNetscape6|NS 6\\nNetscape/6|NS 6\\nNetscape7|NS 7\\nNetscape/7|NS 7\\nGaleon|Galeon\\nMinimo|Minimo\\nNetFront|NetFront\\nGecko|Gecko\\niCab|iCab\\nMozilla/4|NS 4\\nWebCrawler|Crawler\\nNutch|Crawler\\nia_archiver|Crawler\\ninktomi|Crawler\\nVoilaBot|Crawler\\nTeleport Pro|Crawler\\nWebStripper|Crawler\\nWebZIP|Crawler\\nNetcraft Web|Crawler\\nInternetSeer|Crawler\\nScooter|Crawler\\nKSbot|Crawler\\nzyborg|Crawler\\nNG|Crawler\\nNaverBot|Crawler\\nTurnitinBot|Crawler\\npsbot|Crawler\\nNaverRobot|Crawler\\nalmaden.ibm.com|Crawler\\nantibot|Crawler\\nPompos|Crawler\\nHenriLeRobotMirago|Crawler\\ngrub|Crawler\\nWget|Crawler\\nCrawler|Crawler\\ncrawler|Crawler\\nFranklin|Crawler\\nAsk Jeeves|Crawler\\nIDBot|Crawler\\nValidator|Crawler\\nDumbot|Crawler\\nBecomeBot|Crawler\\nMJ12bot|Crawler\\ne-SocietyRobot|Crawler\\nStackRambler|Crawler\\nYandex|Crawler\\nIndy Library|Crawler\\nlwp-trivial|Crawler\\nSnapbot|Crawler\\nMuncher|Crawler\\nOnetSzukaj|Crawler\\nBaiduspider|Crawler\\nSzukacz|Crawler\\nGigamega.bot|Crawler \\nigdeSpyder|Crawler \\nlibwww-perl|Crawler \\nCharlotte|Crawler\\noBot|Crawler \\neSyndiCat|Crawler \\nLiteFinder|Crawler \\nMail.Ru|Crawler \\nJava|Crawler \\nMSFrontPage|Crawler\\nWebDAV|Crawler\\ncazoodle|Crawler\\nGigabot|Crawler\\nDotBot|Crawler\\nWordPress|Crawler\\noldscriptfinder|Crawler\\nspider|Crawler\\nY!J-BSC|Crawler\\nproximic|Crawler\\nscoutjet|Crawler\\nSUNPlex|SunPlex\\nMSIE 1|MSIE 1\\nMSIE 3|MSIE 3\\nMSIE 4|MSIE 4\\nLynx/2|Lynx 2\\nHTTrack|HTTrack\\nOmniWeb|OmniWeb\\nOpera 3|Opera 3\\nOpera/3|Opera 3\\nOpera 4|Opera 4\\nOpera/4|Opera 4\\nOpera 5|Opera 5\\nOpera/5|Opera 5\\nOpera 6|Opera 6\\nOpera/6|Opera 6\\nOpera 7|Opera 7\\nOpera/7|Opera 7\\nBlazer|Blazer\\nMozilla/3|NS 3\\nKonqueror/2|Konqueror 2\\nMozilla/0.91|Mosaic";

$os_list = "Windows NT 6.0|Win Vista\\nWindows NT 5.2|Win 2003 \\nWindows NT 5.1|Win XP\\nWindows XP|Win XP\\nMacintosh|Mac\\nAndroid|Android\\nlinux|Linux\\nLinux|Linux\\nRedHat|Linux\\nPalm|Palm OS\\nWindows 2000|Win 2000\\nWindows NT 5.0|Win 2000\\nWin 9x|Win 98\\nWin98|Win 98\\nWindows 98|Win 98\\niPhone|iPhone\\nWindows CE|Win CE\\nPlayStation Portable|Sony PSP\\nPLAYSTATION|Sony Playstation\\nSonyEricsson|Sony Ericsson\\nNokia|Nokia\\nBlackBerry|BlackBerry\\nMac_PPC|Mac\\nMac_PowerPC|Mac\\nWindows ME|Win Me\\nSymbian|Symbian OS\\nJ2ME|Java ME\\nWin95|Win 95\\nWinNT|Win NT\\nWin32|Win\\nWindows 95|Win 95\\nWindows_95|Win 95\\nWindows NT|Win NT\\nWindows|Win\\nSunOS|SunOS\\nSUNPlex|SunOS\\nOSF1|OSF1\\nHP-UX|HP Unix\\nIRIX|SGI IRIX\\nNetBSD|NetBSD\\nFreeBSD|FreeBSD\\nOpenBSD|OpenBSD\\nbeOS|beOS\\nKonqueror|Linux";

$search_engines = "Google|google|as_q:q:query:as_epq\\nMSN|msn|q\\nYahoo|yahoo|p:va:vp\\nLive Search|search.live.com|q\\nBing|bing.com|q\\n123people|123people|search_term\\nGoogle Cache|209.85.229.132|q\\nAlice|alice|qs\\n7Metasearch|7metasearch.com|q\\n7Search|7search.com|qu\\nAbout|about.com|terms\\nAllTheWeb|alltheweb.com|query:q\\nAllTheInternet|alltheinternet.com|q\\nAltavista|altavista.com|q\\nAOL|aol|query:q:r\\nAsk|ask.co.uk|q\\nAsk.com|ask.com|q\\nATT|att.net|qry\\nBellsouth|home.bellsouth.net|Keywords:string\\nBiglobe Japan|cgi.search.biglobe.ne.jp|q\\nBlueWin Switzerland|bluewin.ch|q:qry\\nClub Internet (France)|club-internet.fr|q\\nChello|chello|srchText:q1\\nDevilfinder.com|devilfinder.com|q\\nOpen Directory Project|dmoz.org|search\\nDogpile|dogpile.com|q:q_all\\nElmundo Spain|ariadna.elmundo.es|q\\nExalead|exalead|q\\nEniro.fi|eniro.fi|q\\nEniro.se|eniro.se|q\\nExcite|excite|search:q\\ngazeta.pl|szukaj.gazeta.pl|slowo\\nGoogle/Gotonet|gotonet.google|q\\nGoogle Groups|groups.google|q\\nGoogle Images|images.google|prev\\nGoogle WAP|wap.google|q:query\\nHanafos|hanafos.com|query\\nHispatvista|buscar.hispavista.com|cadena\\nHotBot|hotbot|query\\nIOL|iol|B1:q:query\\ninteria.pl|www.google.interia.pl|q\\nIXQuick|ixquick.com|query\\nIwon.com|iwon.com|searchfor:q\\nKartoo.com|kartoo.com|q\\nKolumbus Finland|kolumbus.fi|q\\nKvasir.no|kvasir|q\\nLibero|arianna.libero.it|query\\nLookSmart|looksmart.com|key:qt\\nLycos|lycos|MT:query\\nMamma.com|mamma.com|query\\nMirago|mirago|qry:txtSearch\\nMSN|msn|MT,q:q\\nMyNet|arama.mynet.com|q\\nMyWay|myway.com|searchfor\\nMyWay|mywebsearch.com|searchfor\\nNANA Israel|nana.co.il|string:q\\nNaver|naver.com|query:oldquery\\nNetBul|kapi.netbul.com|keyword\\nNetscape|netscape|search:q:query\\nNetsprint.pl|www.netsprint.pl|q\\nNomade France|nomade|MT:s\\nNeuf.fr|neuf.fr|Keywords\\no2.pl|szukaj2.o2.pl|qt\\nonet.pl|szukaj.onet.pl|qt\\nOpera|search.opera.com|search\\nOverture|overture.com|Keywords\\nSearch.com|search.com|q\\nSeznam.cz|seznam.cz|q\\nCentrum.cz|centrum.cz|q \\nSzukacz|www.szukacz.pl|q\\nSpray Sweden|spray.se|query\\nSympatico|sympatico|query\\nTele2internet|tele2internet.fr|query\\nT-Online(Germany)|t-online.de|q\\nToile.com|toile.com|q\\nTut.by|tut.by|query\\nUOL Argentina|uol.com|q\\nVoila|voila|rdata:kw|iso-8859-1\\nYa|buscador.ya.com|q:item\\nWalla Israel|find.walla.co.il|q\\nWeb.de|web.de|su\\nWebsearch.com|websearch.com|qkw\\nWWW Finland|www.fi|query\\nWebalta|webalta.ru|q\\nwp.pl|szukaj.wp.pl|szukaj\\nya.com|ya.com|q\\nzworks|zworks.com|what\\nAport|aport.ru|r|Windows-1251\\nBigmir.net|bigmir.net|q|Windows-1251\\nFast-diets|fast-diets.ru|word|Windows-1251\\nGougle|gougle.ru|q|Windows-1251\\nMail|mail.ru|q|Windows-1251\\nMamont|mmnt.ru|st|Windows-1251\\nMeta.ua|meta.ua|q|Windows-1251\\nNigma|nigma.ru|q:s:request_str\\nPoisk|poisk|text|Windows-1251\\nRambler|rambler.ru|query \\nRambler|rambler.ru|words|Windows-1251 \\nRambler|r0.ru|words|Windows-1251\\nRefers|refers.ru|query|Windows-1251\\nGoGo|gogo.ru|q|Windows-1251\\nReknet|reknet.ru|query|Windows-1251\\nYandex|yandex.ru|text\\nConduit(google)| ​conduit.​com|​q\\nYanga|​yanga.co.uk|​q";

$blacklist = "miragorobot.com\\naolrecherche\\nantibot.net\\nnetscape\\nsearch1-2.free.fr\\nsearch1-1.free.fr\\n+++++++++++++++\\nwww.planetis.com\\naolsearch.aol.com\\nwww.fastbot.com\\nOutpost\\nesearchandfind.org\\nslt.alexa.com\\nnumericable.fr\\nonline-pharmacy\\nbuy-\\nwww.omagiu.net\\nrx.paromi.net\\nmx.gs\\nwww.automatique-marketing.com\\nthomasblake.floridavirtualhomes.com\\ncams-sex-live\\ncollegefuckfest\\nallinternal\\nbrutalblowjobs\\nmedsgenerica\\n ​ fuckherthroat\\nasstraffic\\nwrongsideoftown\\nlucky-blackjack\\nsexyhit\\nmp3main.com\\nviagra\\ncialis\\nporno\\nbest-replica\\ndiet-pill\\njanssen-beauty\\npills. ​ com\\nfree--pics.org\\nwww.dekorfilm.se/\\nwww.ithink.pl/sklep-wedkarski/\\nporn\\nfiskekommunerna\\npornstars\\nwww.southfloridacondos.org/\\nwww.florida-condos.org";


$ipbanlist = array( array(1503002185, 0), // 89.149.254.73 to 89.149.254.73
array(3563010647, 0), // 212.95.58.87 to 212.95.58.87
array(1502999742, 0), // 89.149.244.190 to 89.149.244.190
array(3563010653, 0), // 212.95.58.93 to 212.95.58.93
array(1094150912, 255), // 65.55.107.0 to 65.55.107.255
array(1295124391, 0), // 77.50.7.167 to 77.50.7.167
array(1094182656, 255), // 65.55.231.0 to 65.55.231.255
array(1407821144, 0), // 83.233.165.88 to 83.233.165.88
array(3430725442, 0), // 204.124.183.66 to 204.124.183.66
array(1358824362, 0), // 80.254.3.170 to 80.254.3.170
array(3572198177, 0), // 212.235.107.33 to 212.235.107.33
array(2903757061, 0), // 173.19.209.5 to 173.19.209.5
array(1387123051, 0), // 82.173.209.107 to 82.173.209.107
array(1094151168, 255), // 65.55.108.0 to 65.55.108.255
array(1503002125, 0), // 89.149.254.13 to 89.149.254.13
array(1094165760, 255), // 65.55.165.0 to 65.55.165.255
array(1094182912, 255), // 65.55.232.0 to 65.55.232.255
array(1382227456, 127), // 82.99.30.0 to 82.99.30.127
array(1094151424, 511), // 65.55.109.0 to 65.55.110.255
array(3264889386, 0), // 194.154.66.42 to 194.154.66.42
array(3585972875, 0), // 213.189.154.139 to 213.189.154.139
array(1086272098, 0), // 64.191.50.98 to 64.191.50.98
array(404154472, 0) ); // 24.22.232.104 to 24.22.232.104

$save_host = "400";
$ntop = "10";
$ntoplong = "15";
$seref = "0";
$visitcutoff = "15";
$stringLengthsFactor = "10";
$referrerNewDuration = "4";
$cleanRefIPKwdPath = "0";
$cleanRefIPKwdPathInt = "5";
$cleanAccess = "0";
$cleanAccessInt = "5";

$version = "2.3";
$sqlcreate = "CREATE TABLE `$config_table` ( `variable` varchar(32) NOT NULL default '', `value` text NOT NULL ) $engine_or_type=MyISAM
INSERT INTO `$config_table` VALUES ('browser_list', '$browser_list')
INSERT INTO `$config_table` VALUES ('os_list', '$os_list')
INSERT INTO `$config_table` VALUES ('save_host', '$save_host')
INSERT INTO `$config_table` VALUES ('search_engines', '$search_engines')
INSERT INTO `$config_table` VALUES ('blacklist', '$blacklist')
INSERT INTO `$config_table` VALUES ('ntop', '$ntop')
INSERT INTO `$config_table` VALUES ('ntoplong', '$ntoplong')
INSERT INTO `$config_table` VALUES ('seref', '$seref')
INSERT INTO `$config_table` VALUES ('visitcutoff', '$visitcutoff')
INSERT INTO `$config_table` VALUES ('stringLengthsFactor', $stringLengthsFactor)
INSERT INTO `$config_table` VALUES ('version', '$version')
INSERT INTO `$config_table` VALUES ('referrerNewDuration', '$referrerNewDuration')
INSERT INTO `$config_table` VALUES ('cleanRefIPKwdPath', '$cleanRefIPKwdPath')
INSERT INTO `$config_table` VALUES ('cleanRefIPKwdPathInt', '$cleanRefIPKwdPathInt')
INSERT INTO `$config_table` VALUES ('lastCleanRefIPKwdPath', '1975-01-01')
INSERT INTO `$config_table` VALUES ('cleanAccess', '$cleanAccess')
INSERT INTO `$config_table` VALUES ('cleanAccessInt', '$cleanAccessInt')
INSERT INTO `$config_table` VALUES ('lastCleanAccess', '1975-01-01')";

$sqlCreateSite = "CREATE TABLE `${config_table}_sites` ( `id` MEDIUMINT NOT NULL , `table` VARCHAR( 100 ) NOT NULL , `site` VARCHAR( 255 ) NOT NULL , `public` BOOL NOT NULL , `trim` BOOL NOT  NULL , `crawler` BOOL NOT NULL , `counter` BOOL NOT NULL, `timediff` tinyint(4) NOT NULL default '0' ) $engine_or_type=MYISAM";

$sqlCreateIPBan = "CREATE TABLE `${config_table}_ipban` ( `id` smallint(5) unsigned NOT NULL auto_increment, `ip` bigint(11) NOT NULL default '0', `range` int(11) NOT NULL default '0', `date` date NOT NULL default '0000-00-00', `last` date NOT NULL default '0000-00-00', `count` mediumint(9) NOT NULL default '0', PRIMARY KEY  (`id`) ) $engine_or_type=MyISAM";

$upgradeWB = "UPDATE `$config_table` SET value='$browser_list' WHERE variable='browser_list'";
$upgradeOS = "UPDATE `$config_table` SET value='$os_list' WHERE variable='os_list'";
$upgradeSE = "UPDATE `$config_table` SET value='$search_engines' WHERE variable='search_engines'";

?>