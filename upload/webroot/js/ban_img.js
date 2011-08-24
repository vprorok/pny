	$(function() {
			$('#bn_left').crossSlide({
				sleep: 5, //in sec
				fade: 1   //in sec
			},
			[
				{ src: '/themed/template2/img/ban/guide304.gif', href: '/page/guide' },
				{ src: '/themed/template2/img/ban/regist304.gif', href: '/users/register' },
			]);
			$('#bn_center').crossSlide({
				sleep: 10, //in sec
				fade: 1   //in sec
			},
			[
				{ src: '/themed/template2/img/ban/guide304.gif', href: '/page/guide' },
				{ src: '/themed/template2/img/ban/regist304.gif', href: '/users/register' },
			]);
			$('#bn_right').crossSlide({
				sleep: 15, //in sec
				fade: 1   //in sec
			},
			[
				{ src: '/themed/template2/img/ban/guide304.gif', href: '/page/guide' },
				{ src: '/themed/template2/img/ban/regist304.gif', href: '/users/register' },
			]);
			$('#bn_left_auth').crossSlide({
				sleep: 5, //in sec
				fade: 1   //in sec
			},
			[
				{ src: '/themed/template2/img/ban/guide304.gif', href: '/page/guide' },
				{ src: '/themed/template2/img/ban/regist304.gif', href: '/users/register' },
			]);
			$('#bn_center_auth').crossSlide({
				sleep: 10, //in sec
				fade: 1   //in sec
			},
			[
				{ src: '/themed/template2/img/ban/guide304.gif', href: '/page/guide' },
				{ src: '/themed/template2/img/ban/regist304.gif', href: '/users/register' },
			]);
			$('#bn_right_auth').crossSlide({
				sleep: 15, //in sec
				fade: 1   //in sec
			},
			[
				{ src: '/themed/template2/img/ban/guide304.gif', href: '/page/guide' },
				{ src: '/themed/template2/img/ban/regist304.gif', href: '/users/register' },
			]);
	});


	// header_banner_space
	$(document).ready(function(){
		$("#menu").css({display:"block"}),
		$("#button").click(function(){
			$("#menu").slideToggle("normal");
		}).css({
			cursor:"pointer"
		});
	});
