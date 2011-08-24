<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Korean translation by KIM JaeGeun/DoA, http://qaos.com/

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
<title>phpTrafficA 도움말</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">창 닫기</a></div>
<h1>phpTrafficA 도움말</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>차단 목록</strong>: 이 폼은 참조 URL 차단 목록에 사용됩니다. 예를들어 웹 사이트 접근에 사용된 검색어를 추출할 수 없도록 하는 일부 검색 엔진이 있습니다. 이런 검색 엔진은 검색 통계에 포함되어서는 안됩니다. 또 스팸 참조 URL은 카운트되서도 안됩니다. 이 기능은 <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referrer spam</A>을 보내는 사이트를 차단하는데 사용됩니다..</li>

<li><A name="domain"></A><strong>도메인</strong>: 추적할 도메인 이름을 <code>myserver.net/mydirectory/</code>처럼 입력하세요</li>

<li><A name="savehost"></A><strong>최근 호스트</strong>: phpTrafficA은 최근 방문자에 대한 전체 정보를 가진 테이블을 사용합니다. 이들은 공간 절약을 위해 바로 삭제되며 추출된 정보는 적당한 형태로 유지됩니다. 이 테이블은 <code>최근 방문자</code> 탭과 <code>경로 해석</code>에서 데이타 처리에 사용됩니다. 만약 이 기능에 더 많은 호스트를 유지하려면 저장된 호스트의 수를 키우면 됩니다. 그러나 호스트의 수를 키우면 처리 속도가 극단적으로 느려질 수 있습니다.</li>

<li><A name="oslist"></A><strong>운영체제 목록</strong>: 이 테이블은 운영체제 목록과 브라우저 정보에서 운영체제를 추출하는 방법을 표시합니다. 각 줄은 운영체제를 구분할 수 있는 문자열과 운영체제 이름을 포함하고 있습니다. 문자열과 운영체제 이름은 <code>|</code>로 구분됩니다. 이 테이블은 phpTrafficA 업그레이드시 갱신됩니다.</li>

<li><A name="public"></A><strong>공개</strong>: 모든 방문자는 <code>공개</code>한 도메인에 관련된 통계를 볼 수 있습니다. <code>비공개</code> 도메인에 대한 통계는 로그인한 사람만 볼 수 있습니다</li>

<li><A name="selist"></A><strong>검색 엔진 목록</strong>: 이 표는 검색 엔진의 목록과 검색 엔진을 찾는 방법, 참조 URL에서 검색어를 추출하는 방법을 표시합니다. 각 줄은 한 검색 엔진에 대한 정보를 포함하며 <code>|</code> 기호로 분리합니다. 첫번째 항목은 검색 엔진의 이름, 두번째 항목은 참조 URL에서 검색 엔진을 찾는데 사용하는 문자열, 세번째 항목은 검색 엔진에서 검색어를 전달하는데 사용되는 변수(변수가 여러개인 경우 <code>:</code>으로 구분)입니다. 예를들어 구글은 URL <code>google.com</code>과 변수 <code>q</code>를 사용합니다. 이 표는 phpTrafficA를 업그레이드할 때 갱신됩니다.</li>

<li><A name="table"></A><strong>표</strong>: SQL 테이블 이름에 대한 접두어. phpTrafficA가 추적할 각 사이트에 대해 여러 테이블을 만들 수 있습니다. 이 테이블들의 이름은 항상 여기에 제공한 문자열로 시작하며 _가 이어집니다. 예를들어 blog로 지정하면 blog_로 테이블 이름을 시작합니다.</li>

<li><A name="trim"></A><strong>URL 자르기</strong>: <code>예</code>로 설정하면, 통계에 사용된 URL을 자르게됩니다. 예를들어, <code>index.php?lang=fr</code>와 <code>index.php?lang=en</code>는 <code>index.php</code>로 저장됩니다. 이 값이 phpTrafficA에서 기본값입니다. 동적인 웹 사이트에서 URL의 조합은 비정상적으로 많이 나올 수 있기 때문에 통계에서 완전한 URL을 사용하는 것은 주의해야 합니다. 더우기 <strong>이미 phpTrafficA를 이용해서 추적하고 있다면 이 값을 바꾸지 않는 것이 좋습니다</strong>.</li>

<li><A name="wblist"></A><strong>웹 브라우저 목록</strong>: 이 테이블은 웹 브라우저의 목록과 웹 브라우저를 찾는 방법을 표시합니다. 각 줄은 브라우저를 구분하는 문자열을 포함하고 있으며, 브라우저 이름이 이어집니다. 문자열과 브라우저의 이름은 <code>|</code>로 구분합니다. 이 테이블은 phpTrafficA를 업그레이드할 때 새로 고쳐집니다.</li>

<li><A name="countbots"></A><strong>봇 계산</strong>: 이 옵션을 선택하면 사이트를 방문한 로봇(googlebot, yahoo slurp 과 같은)도 정상적인 방문자로 계산합니다. 이 옵션을 선택하지 않으면 봇은 통계에 포함되지 않습니다. 이 봇들은 최근 호스트 테이블에서 볼 수 있지만 그것이 전부입니다.</li>

<li><A name="counter"></A><strong>카운터</strong>: 이 옵션을 선택하면 phpTrafficA는 카운터로 동작합니다. 통계를 기록하기 위해 이미지 스크립트 중 하나를 선택하면 이미지는 기록을 시작한 뒤 지금까지의 히트수를 포함하게 됩니다. 통계를 기록하기 위해 PHP를 사용한다면 phpTrafficA는 현재 페이지에 대한 히트수를 표시합니다.</li>

<li><A name="magnetindex"></A><strong>자석 지표</strong>: <code>자석 지표</code>는 주어진 페이지가 얼마나 많은 트래픽을 가져오는지를 측정할 때 유용한 도구입니다. 예를들어, 1, 2, 3의 <code>자석 지표</code>를 같는 페이지는 하루에 10, 100, 1000 히트의 입장 페이지를 의미합니다. 이 지표를 페이지의 평균 히트와 혼동해서는 안됩니다. 방문자가 이 페이지를 입장 페이지로 사용할 때만 지표를 계산합니다.</li>

<li><A name="bouncerate"></A><strong>반송율</strong>: <code>반송율은</code>이 페이지를 본 뒤 다시 사이트를 방문하는 사람들의 백분율을 말해 주는 아주 중요한 척도입니다.</li>

<li><A name="sereferrers"></A><strong>검색 엔진을 참조 URL로</strong>: 이 옵션을 선택하면 검색 질의어는 참조 URL 테이블에도 표시됩니다. 이 옵션으로 웹 사이트를 접근하든데 사용된 완전한 검색 URL을 알 수 있습니다. 즉 참조 URL 테이블이 사실상 커치며 따라서 이 옵션은 주의깊게 사용해야 합니다.</li>

<li><A name="visitcutoff"></A><strong>방문 결산 시간</strong>: 이 옵션은 방문 결산 시간을 분 단위로 설정합니다. 만약 고유 방문자(IP 주소를 기초로한)가 결산 시간 이상 활동이 없다면 같은 IP의 다음 번 히트는 새로운 고유 방문자로 처리됩니다. 기본값은 15분입니다.</li>

<li><A name="timediff"></A><strong>시차</strong>: 서버의 시간대가 실제 시간대와 다르다면 이 옵션을 사용합니다. 시차를 시간 단위로 설정하면 됩니다.</li>

<li><A name="URLTrimFactor"></A><strong>URL 자르기 인자</strong>: 여러 phpTrafficA 페이지에서 문자열과 URL의 길이를 설정할 때 이 옵션을 사용합니다. 기본값은 10입니다. 20을 선택하면 문자열과 URL은 기본보다 두배 큰 값을 가지게됩니다. 5값을 선택하면 문자열과 URL은 기본값의 절반 값을 가지게 됩니다.</li>

<li><A name="referrerNewDuration"></A><strong>Time to keep referrer marked as new</strong>: new addresses in the referrer page will be marked as <code>new</code> until you click on the link. Referrers older that this setting will not be marked as new, even if you did not click on the link.</li>

<li><A name="autoCleanRKIP"></A><strong>Automatic cleanup of referrer, keyword, IP list, and path tables</strong>: if you choose this option, tables with referrers, keywords, IP addresses, and paths will be automatically cleaned regularly. This will remove entries older than 2 months and that have been used only once.</li>

<li><A name="autoCleanAccess"></A><strong>Automatic cleanup of access tables</strong>: if you choose this option, access tables (page counts and unique visitors) will be automatically cleaned regularly. This will remove data older than two months. The total number of acces to each page and the statistics for the whole site will be preserved, but all acces data to individual pages older that two months will be lost.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>