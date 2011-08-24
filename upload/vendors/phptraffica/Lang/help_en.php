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
<title>phpTrafficA helpdesk</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Close window</a></div>
<h1>phpTrafficA helpdesk</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Black list</strong>: this forms is used to blacklist buggy referrers. For instance, they are a few search engines that do not allow the extraction of the keywords used to access the website, they can not be included in the search engines statistics, but they are not and should not be counted as referrers. This function is also used to ban sites that perform <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referrer spam</A>.</li>

<li><A name="domain"></A><strong>Domain</strong>: enter the root of the domain you want to track, something like <code>myserver.net/mydirectory/</code></li>

<li><A name="savehost"></A><strong>Latest hosts</strong>: phpTrafficA keeps a table with the full information of the latest visitors. After some time they are simply deleted in order to save space and information is kept in a processed form. This table is used in the <code>Latest visits</code> tab and for data processing in the <code>Path Analysis</code>. If you want to have more hosts included in those functions, increase the number of saved hosts. phpTrafficA might become extremely slow with large tables.</li>

<li><A name="oslist"></A><strong>OS list</strong>: list of operating systems. This table displays the list of operating systems and how to detect them in the browser identification. Each line contains an identification string followed by the OS name, separated by the symbol <code>|</code>. This table is usually updated with phpTrafficA upgrades.</li>

<li><A name="public"></A><strong>Public</strong>: all visitors are allowed to statistics related to <code>public</code> domains. Statistics for <code>private</code> domains are only available when you log in.</li>

<li><A name="selist"></A><strong>Search engine list</strong>: this table displays the list of search engines, how to detect them, and how to extract keywords in the referrer string. Each line contains information for one search engine, separated by the symbol <code>|</code>. First item is a name, second item is a string used to detect the search engine in the referrer url, and third item is the variable this search engine uses to pass keywords (separated by the symbol <code>:</code> for multiple possibilities). For instance, a search in google will be detected with the URL <code>google.com</code> and the variable <code>q</code>. This table is updated with phpTrafficA upgrades.</li>

<li><A name="table"></A><strong>Table</strong>: base name for sql tables. We create several table for each website tracked by phpTrafficA. Their name always start with the string provided here.</li>

<li><A name="trim"></A><strong>Trim URL</strong>: if this this set to <code>true</code>, URL used in the statistics will be trimmed. For instance, <code>index.php?lang=fr</code> and <code>index.php?lang=en</code> will all be stored as <code>index.php</code>. This is the default behavior in phpTrafficA. Be careful if you decide to keep the full URL for your statistics as the number of combinations of URL can be immense for fully dynamic websites. Moreover, <strong>it is not recommended to change this parameter once you have used phpTrafficA for a while</strong>.</li>

<li><A name="wblist"></A><strong>Web browser list</strong>: list of web browsers. This table displays the list of web browsers and how to detect them in the browser identification. Each line contains an identification string followed by the browser name, separated by the symbol <code>|</code>. This table is updated with phpTrafficA upgrades.</li>

<li><A name="countbots"></A><strong>Count bots</strong>: if you select this option robots that visit your site (googlebot, yahoo slurp and such) will be counted as regular visitors. If you do not select this option, they will not be included in the statistics. You will see them in the latest hosts table, but that's all.</li>

<li><A name="counter"></A><strong>Counter</strong>: if you select this option phpTrafficA will also act as a counter. If you selected one of the image scripts for recording your stats, the image will include the number of hits since the start of the record. If you selected a pure php technique to record your stats (no image), phpTrafficA will display the total number of hits for the current page.</li>

<li><A name="magnetindex"></A><strong>Magnet Index</strong>: the <code>magnet index</code> is a useful tool as it measures how much traffic is brought to your site by a given page. For instance, pages with a <code>magnet index</code> of 1, 2, and 3 are entry pages for 10, 100, 1000 hits a day, respectively. Do not mix up this factor with the average number of hits at a given page. We count all hits from visits starting on this page, not only visits to this page.</li>

<li><A name="bouncerate"></A><strong>Bounce rate</strong>: the <code>bounce rate</code> is an important metric as it tells you the percentage of people who 'bounced' away (left) from your site after viewing this page only.</li>

<li><A name="sereferrers"></A><strong>Search engines are referrers</strong>: If you select this option, search engine queries will be listed in the referrer table as well. This will allow you to have the full URL of all searches leading to your website. On the other hand, your referrer table might become really large, so use this option with caution, and only if you have a lot of disk space!</li>

<li><A name="visitcutoff"></A><strong>Visit cut-off time</strong>: This option sets the visit cut-off time, in minutes. If a unique visitor (based on IP addresses) has not been active for more than this cut-off time, the next hit from this same IP address will be treated as a new unique visit. Default value is 15 minutes.</li>

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