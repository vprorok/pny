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
<title>phpTrafficA. Помощь</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Закрыть окно</a></div>
<h1>phpTrafficA. Помощь</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Чёрный список</strong>: эта форма используется, чтобы поместить в черный список источники. Например, здесь показаны несколько поисковых систем, из которых нельзя извлечь ключевые слова, используемые для получения доступа к вебсайту. Они не могут быть включены в статистику поисковых систем, но они должны отсутствовать и не быть подсчитанными как источники. Эта функция также используется для того, чтобы запретить сайты, которые выполняют <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referrer спам</A>.</li>

<li><A name="domain"></A><strong>Домен</strong>: введите url сайта без http:// со слешем в конце, для которого Вы хотите вести статистику, например <code>myserver.net/</code>.</li>

<li><A name="savehost"></A><strong>Последние посетители</strong>: phpTrafficA записывает таблицу с полной информацией о последних посетителях. Через некоторое время они будут удалены, чтобы сохранить место в базе данных, но информация будет сохранена в обработанной форме. Этот список используется в таблице <code>Последние посетители</code> для обработки данных в <code>Анализ пути</code>. Если Вы хотите сохранять больше число посетителей, включенных в эти функции, увеличьте число. phpTrafficA может работать очень медленно с большими таблицами.</li>

<li><A name="oslist"></A><strong>Операционные системы</strong>: список операционных систем. Эта таблица показывает список операционных систем и как обнаружить их в идентификационной информации браузера. Каждая линия содержит строку идентификации и название ОС, разделённые символом <code>|</code>. Эта таблица обновляется с обновлениями phpTrafficA.</li>

<li><A name="public"></A><strong>Публично</strong>: любой посетитель может посмотреть статистику домена, указанную как <code>публично</code>. Статистика для доменов <code>по паролю</code> доступна, только когда Вы введёте пароль.</li>

<li><A name="selist"></A><strong>Список поисковых систем</strong>: эта таблица показывает список поисковых систем, как их обнаружить и как извлечь ключевые слова в строке источника. Каждая линия содержит информацию для одной поисковой системы, отделенную символом <code>|</code>. Первый пункт - название, второй пункт - строка, используемая для того, чтобы обнаружить поисковую систему в referrer url, и третий пункт - переменная, используемая этой поисковой системой для передачи ключевых слов (отделенных символом <code>:</code> для нескольких вариантов). Например, поиск в google будет обнаружен по URL <code>google.com</code> и переменной <code>q</code>. Эта таблица обновляется с обновлениями phpTrafficA.</li>

<li><A name="table"></A><strong>Название таблицы</strong>: префикс для имени sql таблиц. Мы создаем несколько таблиц для каждого вебсайта, для которого будет вестись статистика. Их название всегда начинается со строки, указанной здесь.</li>

<li><A name="trim"></A><strong>Обрезать URL</strong>: если установлено <code>да</code>, URL, используемый в статистике будет урезан. Например, <code>index.php?lang=fr</code> и <code>index.php?lang=en</code> будут сохранены как <code>index.php</code>. Эта установка по умолчанию в phpTrafficA. Будьте осторожны, если Вы решите хранить полный URL в Вашей статистике, поскольку число комбинаций URL может быть огромно для полностью динамических вебсайтов. Кроме того, <strong>не рекомендуется изменять этот параметр в начале использования phpTrafficA</strong>.</li>

<li><A name="wblist"></A><strong>Список web-браузеров</strong>: список web-браузеров. Эта таблица показывает список web-браузеров и как обнаружить их в идентификационной информации браузера. Каждая линия содержит строку идентификации и название браузера, разделённые символом <code>|</code>. Эта таблица обновляется с обновлениями phpTrafficA.</li>

<li><A name="countbots"></A><strong>Считать роботов</strong>: Если Вы выберете эту настройку, то роботы (googlebot, yahoo slurp и другие), посетившие Ваш сайт, будут учитаны как обычные посещения. Если не выберете эту настройку, роботы не будут показаны в статистике. Вы сможете увидеть их только в таблице последних посещений и нигде больше.</li>

<li><A name="counter"></A><strong>Счётчик</strong>: если Вы выберете эту опцию, phpTrafficA будет действовать как счётчик. Если Вы выбрали один из скриптов с картинкой для ведения статистики, будет учитано число хитов, начиная с начала записи. Если Вы выбрали чистый php, phpTrafficA покажет общее количество хитов для текущей страницы.</li>

<li><A name="magnetindex"></A><strong>Индекс притяжения</strong>: <code>индекс притяжения</code> - полезный инструмент, так как измеряет, сколько трафика приведено к Вашему сайту данной страницей. Например, страницы с <code>индекс притяжения</code> 1, 2, и 3 - страницы входа для 10, 100, 1000 хитов в день соответственно. Не перепутайте этот фактор со средним числом посещений данной страницы. Мы считаем все посещения, начинающиеся с этой страницы, а не просто посещения этой страницы.</li>

<li><A name="bouncerate"></A><strong>Индекс ухода</strong>: <code>Индекс ухода</code> - важный показатель, поскольку он показывает процент людей, которые ушли с Вашего сайта после рассмотрения только этой страницы.</li>

<li><A name="sereferrers"></A><strong>Поисковые системы и источники</strong>: Если Вы выберете эту опцию, то запросы поисковых систем будут внесены в список источников. Это позволит Вам иметь полный список всех URL, приводящих к вашему сайту. С другой стороны, ваша таблица источников может стать очень большой, поэтому используйте эту опцию с осторожностью, и только если на Вашем сайте есть большое дисковое пространство!</li>

<li><A name="visitcutoff"></A><strong>Законченные уникальные визиты (в минутах)</strong>: Эта опция устанавливает время законченного уникального посещения, в минутах. Если уникальный посетитель (определенный по IP адресу) не был активен в течение более чем этого времени, следующий хит с этого же самого IP адреса будет рассматриваться как новое уникальное посещение. Значение по-умолчанию - 15 минут.</li>

<li><A name="timediff"></A><strong>Разница во времени</strong>: используйте эту настройку, если ваш сервер с phpTrafficA не в той же временной зоне, что и ваш анализируемый сайт. Установите разницу во времени, в часах.</li>

<li><A name="URLTrimFactor"></A><strong>Ограничение длины URL</strong>: используйте эту настройку для установки длинны строк и URL на страницах статистики. Значение по-умолчанию 10. Если вы поставите 20, строки и URL будут длиннее, чем по-умолчанию. Если вы поставите 5, то короче.</li>

<li><A name="referrerNewDuration"></A><strong>Time to keep referrer marked as new</strong>: new addresses in the referrer page will be marked as <code>new</code> until you click on the link. Referrers older that this setting will not be marked as new, even if you did not click on the link.</li>

<li><A name="autoCleanRKIP"></A><strong>Автоматическая очистка таблиц с источниками, ключевыми словами, IP адресами и путями</strong>: if you choose this option, tables with referrers, keywords, IP addresses, and paths will be automatically cleaned regularly. This will remove entries older than 2 months and that have been used only once.</li>

<li><A name="autoCleanAccess"></A><strong>Автоматическая очистка таблиц доступа</strong>: if you choose this option, access tables (page counts and unique visitors) will be automatically cleaned regularly. This will remove data older than two months. The total number of acces to each page and the statistics for the whole site will be preserved, but all acces data to individual pages older that two months will be lost.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>