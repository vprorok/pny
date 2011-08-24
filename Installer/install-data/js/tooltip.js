	var Application = {
		init: [],
		Ui: {},
		Util: {
			isDefined: function(obj) { return (typeof(obj) != 'undefined'); },
			isFunction: function(obj) { return jQuery.isFunction(obj); },
			isObject: function(o) { return (o && typeof o == 'object'); },
			isArray: function(o) { return (isObject(o) && o.constructor == Array); },
			submitPost: function(url, parameters) { Application.Util._submitRequest('post', url, parameters); },
			submitGet: function(url, parameters) { Application.Util._submitRequest('get', url, parameters); },
			_submitRequest:					function(method, url, parameters) {
				var form = $('<form method="' + method + '" action="' + url + '" style="display:none;">' + Application.Util._createSubmitRequestData(parameters) + '</form>');
				form.appendTo(document.body);
				form.get(0).submit();
			},
			_createSubmitRequestData:		function(parameters, namePrefix) {
				if(Application.Util.isArray(parameters)) {
					var temp = '';
					if(!namePrefix) namePrefix = 'array';
					for(var i = 0, j = parameters.length; i < j; ++i) {
						temp += Application.Util._createSubmitRequestData(parameters[i], namePrefix + '[' + i + ']');
					}
					return temp;
				} else if(Application.Util.isObject(parameters)) {
					var temp = '';
					if(!namePrefix) namePrefix = '';
					for(var i in parameters) {
						temp += Application.Util._createSubmitRequestData(parameters[i], (namePrefix == ''? i : (namePrefix + '[' + i + ']')));
					}
					return temp;
				} else {
					if(!namePrefix) namePrefix = 'input';
					return '<input type="hidden" name="' + namePrefix + '" value="' + parameters + '" />';
				}
			},

			encrypt: function(s, key) {
				var result = '';
				while (key.length < s.length) {
					key += key;
				}
				for (var i=0; i<s.length; i++) {
					result += String.fromCharCode(key.charCodeAt(i)^s.charCodeAt(i));
				}
				return encodeURIComponent(result);
			},
			decrypt: function(s, key) {
				return Application.Util.encrypt(s, key);
			}
		},

		Info: {
			Browser: {
				_cache: {},
				ie6: function() {
					if(!Application.Util.isDefined(this._cache.ie6))
						this._cache.ie6 = (jQuery.browser.msie && parseFloat(jQuery.browser.version) <= 6);
					return this._cache.ie6;
				}
			}
		},

		Misc: {

			specifyDocumentMinWidth: function(width) {
				if(Application.Misc._specifyDocumentMinWidth) return;
	
				Application.Misc._specifyDocumentMinWidth = {
					minwidth:width,
					donotresize:false,
					resize: function() {
						if(Application.Misc._specifyDocumentMinWidth.donotresize) {
							Application.Misc._specifyDocumentMinWidth.donotresize = false;
							return;
						}
	
						if($('div#IEM_HTML_Body').width() < this.minwidth) {
							$('div#IEM_HTML_Body').css('width', Application.Misc._specifyDocumentMinWidth.minwidth+'px');
							Application.Misc._specifyDocumentMinWidth.donotresize = true;
						} else $('div#IEM_HTML_Body').css('width', 'auto');
					},
					eventDOMReady: function(event) {
						if($.browser.msie && parseInt($.browser.version) == 6) {
							$(window).resize(Application.Misc._specifyDocumentMinWidth.eventWindowResize);
							Application.Misc._specifyDocumentMinWidth.resize();
						} else $('div#IEM_HTML_Body').css('min-width', Application.Misc._specifyDocumentMinWidth.minwidth+'px');
					},
					eventWindowResize: function(event) { Application.Misc._specifyDocumentMinWidth.resize(); }
				};
	
				Application.init.push(Application.Misc._specifyDocumentMinWidth.eventDOMReady);
			},
	

		},

		Page: {},
	

		Modules: {},
	
	
		WYSIWYGEditor: {
			getContent: function() {
				return tinyMCE.activeEditor.getContent();
			},
			setContent: function(content) {
				tinyMCE.activeEditor.setContent(content);
			},
			isWysiwygEditorActive: function() {
				if (typeof(tinyMCE) != 'undefined' && tinyMCE.activeEditor != null) {
					return true;
				}
				return false;
			},
			insertText: function(text) {
				tinyMCE.activeEditor.execCommand('mceInsertContent',false, text);
			}
		},
	
	
		eventDocumentReady: function(event) {
			for(var i = 0, j = Application.init.length; i < j; ++i)
				if(jQuery.isFunction(Application.init[i])) Application.init[i]();
		}
	};
	

	$(document).one('ready', Application.eventDocumentReady);



 	if (!Application.Ui._) Application.Ui._ = {};
 	if (!Application.Ui._.Table) {
 		Application.Ui._.Table = {
 			eventGridRowHover: function() { $(this).toggleClass('GridRowOver'); },
			GridSetup: function() { $('tr.GridRow').hover(Application.Ui._.Table.eventGridRowHover, Application.Ui._.Table.eventGridRowHover); }
 		};
 	}

	Application.init.push(Application.Ui._.Table.GridSetup);




	Application.Ui.Menu = {
		currentMenu: null,
		topCurrentMenu: null,
		topCurrentButton: null,
	
		_: {},
	
		closeMenu: function() {
			if(Application.Ui.Menu.currentMenu) {
				$(Application.Ui.Menu.currentMenu).parent().removeClass('over');
				$(Application.Ui.Menu.currentMenu).parent().find('ul').css('display', 'none');
				$('embed, object, select').css('visibility', 'visible');
				Application.Ui.Menu.currentMenu = null;
			}
	
			if (Application.Ui.Menu.topCurrentMenu) {
				$(Application.Ui.Menu.topCurrentMenu).hide();
				$(Application.Ui.Menu.topCurrentButton).removeClass('ActiveButton');
				if(Application.Info.Browser.ie6()) $('select').css('visibility', '');
				Application.Ui.Menu.topCurrentMenu = null;
				Application.Ui.Menu.topCurrentButton = null;
			}
		}
	};

	if (!Application.Ui.Menu._) Application.Ui.Menu._ = {};
	if (!Application.Ui.Menu._.PopDown) {
		Application.Ui.Menu._.PopDown = {
			eventDocumentReady: function(event) { Application.Ui.Menu.PopDown('.PopDownMenu'); },
			eventMenuClick: function(event) {
				if(jQuery.isFunction(event.data.onClickStart)) event.data.onClickStart(event, this);
				Application.Ui.Menu.closeMenu();
		
				if(Application.Info.Browser.ie6()) $('select').css('visibility', 'hidden');
		
				var id = this.id.replace(/Button$/, '');
				if(!('#'+id)) return false;
		
				var obj = this;
				offsetTop = 0;
				offsetLeft = 0;
				while(obj) {
					offsetLeft += obj.offsetLeft;
					offsetTop += obj.offsetTop;
					obj = obj.offsetParent;
					if(obj && CurrentStyle(obj, 'position')) {
						var pos = CurrentStyle(obj, 'position');
						if(pos == "absolute" || pos == "relative") {
							break;
						}
					}
				}
				obj = null;
		
				$(this).addClass('ActiveButton');
				var menu = $('#'+id);
				menu.css({	'position': 'absolute',
							'top': (offsetTop + this.offsetHeight - (event.data.topMarginPixel)) +"px",
							'left': (offsetLeft + 2) + "px"});
				menu.addClass('PopDownMenuContainer');
		
				this.blur();
				menu.show();
		
				if(event.data.maxHeight != null) {
					var temp = parseInt(event.data.maxHeight);
					if(temp != 0 && menu.height() > temp) {
						$('.DropDownMenu', menu).css({	height: temp+'px',
														overflow: 'auto'});
					}
				}
		
				if(event.data.minHeight != null) {
					var temp = parseInt(event.data.maxHeight);
					if(temp != 0 && menu.height() < temp) {
						$('.DropDownMenu', menu).css({	height: temp+'px'});
					}
				}
		
				Application.Ui.Menu.topCurrentMenu = menu.get(0);
				Application.Ui.Menu.topCurrentButton = this;
				menu = null;
		
				$(document).one('click', {menuid: id}, Application.Ui.Menu._.PopDown.eventCloseMenu);
		
				event.stopPropagation();
				event.preventDefault();
		
				if(jQuery.isFunction(event.data.onClickEnd)) event.data.onClickEnd(event, this);
			},
			eventCloseMenu: function(event) {
				$('#'+event.data.menuid).hide();
				$(Application.Ui.Menu.topCurrentButton).removeClass('ActiveButton');
				Application.Ui.Menu.topCurrentButton = null;
				if(Application.Info.Browser.ie6()) $('select').css('visibility', '');
			}
		};
	}

	$.extend(Application.Ui.Menu, {
		PopDown: function(selector, params) {		
			var defaultParams = {	maxHeight: null,
									minHeight: null,
									onClickStart: null,
									onClickEnd: null,
									topMarginPixel: -1};
	
			$.extend(defaultParams, params || {});
			$(selector).bind('click', defaultParams, Application.Ui.Menu._.PopDown.eventMenuClick);
		}
	});
	

	Application.init.push(Application.Ui.Menu._.PopDown.eventDocumentReady);

	if (!Application.Ui._) Application.Ui._ = {};
	if (!Application.Ui._.HelpToolTip) {
		Application.Ui._.HelpToolTip = {
			eventDOMReady: function(event) {
				var elm = $('span.HelpToolTip');
				for (var i = 0, j = elm.size(); i < j; ++i) {
					var helpTitle = $('span.HelpToolTip_Title', elm.get(i)).html();
					var helpContents = $('span.HelpToolTip_Contents', elm.get(i)).html();
	
					$(elm.get(i)).bind('mouseover', {'title':helpTitle, 'contents':helpContents}, Application.Ui._.HelpToolTip.eventOnMouseOver);
					$(elm.get(i)).mouseout(Application.Ui._.HelpToolTip.eventOnMouseOut);
				}
			},
	
			eventOnMouseOver: function(event) {
				$('<div class="HelpToolTip_Placeholder" style="display:inline; position: absolute; width: 340px; -moz-border-radius: 8px; -webkit-border-radius: 8px; background-color: #FFFFE1; border: solid 1px #F4E4B7; padding: 10px 15px 15px;">'
					+ '<span class="helpTip"><b>' + event.data.title + '</b></span>'
					+ '<br /><div style="padding-left: 10px; padding-right: 5px;">' + event.data.contents + '</div>'
					+ '</div>').appendTo(this);
			},
	
			eventOnMouseOut: function(event) { $('div.HelpToolTip_Placeholder', this).remove(); }
		};
	}
	$.extend(Application.Ui, {
		HelpToolTip: function(selector, title, contents) {	
			$(selector).bind('mouseover', {'title':title, 'contents':contents}, Application.Ui._.HelpToolTip.eventOnMouseOver);
			$(selector).mouseout(Application.Ui._.HelpToolTip.eventOnMouseOut);
		}
	});
	
	Application.init.push(Application.Ui._.HelpToolTip.eventDOMReady);
	if(!Application.Ui._) Application.Ui._ = {};
	Application.Ui._.CheckboxSelection = {
		eventClick: function(event) {
			if($(event.target).is(event.data.parentSelector)) {
				if($(event.target).attr('checked')) {
					$(event.data.childSelector).attr('checked', true);
					if(Application.Util.isFunction(event.data.onSelectAll))
						event.data.onSelectAll();
				} else {
					$(event.data.childSelector).attr('checked', false);
					if(Application.Util.isFunction(event.data.onSelectNone))
						event.data.onSelectNone();
				}
	
	
			} else if($(event.target).is(event.data.childSelector)) {
				var childrens = $(event.data.childSelector);
				var selected = childrens.filter(':checked');
	
				$(event.data.parentSelector).attr('checked', childrens.size() == selected.size());
	
				if(selected.size() == 0) {
					if(Application.Util.isFunction(event.data.onSelectNone))
						event.data.onSelectNone();
					return;
				}
	
				if(childrens.size() == selected.size()) {
					if(Application.Util.isFunction(event.data.onSelectAll))
						event.data.onSelectAll();
					return;
				}
	
				if(Application.Util.isFunction(event.data.onSelectPartial))
					event.data.onSelectPartial();
			}
		}
	};

	Application.Ui.CheckboxSelection = function(container, allSelector, eachSelector, params) {
		var defaultParams = {
			parentSelector: allSelector,
			childSelector: eachSelector,
			onSelectAll: null,
			onSelectNone: null,
			onSelectPartial: null
		};
		$.extend(defaultParams, params || {});
		$(container).bind('click', defaultParams, Application.Ui._.CheckboxSelection.eventClick);
	}

$(document).ready(function() {
	$('#header_menu ul li.dropdown > a').dblclick(function(e)
	{
		e.stopPropagation();
		window.location = this.href;
		return false;
	});

	$('#header_menu ul li.dropdown ul li > a').click(function(e) { Application.Ui.Menu.closeMenu(); });

	$('#header_menu ul li.dropdown > a').click(function(e)
	{
		var elem = this;
		var closeMenuOnly = $(elem).parent().is('.over');

		Application.Ui.Menu.closeMenu();
		$(elem).parent().removeClass('over');
		$('embed, object').css('visibility', 'visible');
		if(isIE6()) $('select').css('visibility', 'visible');
		if (closeMenuOnly) return false;

		Application.Ui.Menu.topCurrentMenu = $(this).parents('li.dropdown').children().get(1);
		Application.Ui.Menu.topCurrentButton = this;

		offsetTop = offsetLeft = 0;
		var element = elem;
		do
		{
			offsetTop += element.offsetTop || 0;
			offsetLeft += element.offsetLeft || 0;
			element = element.offsetParent;
		} while(element);


		$(elem.parentNode).find('ul').css('visibility', 'hidden');
		if(navigator.userAgent.indexOf('MSIE') != -1) {
			$(elem.parentNode).find('ul').css('display', 'block');
		}
		else {
			$(elem.parentNode).find('ul').css('display', 'table');
		}
		var menuWidth = elem.parentNode.getElementsByTagName('ul')[0].offsetWidth;
		$(elem.parentNode).find('ul').css('width', menuWidth-2+'px');
		if(offsetLeft + menuWidth > $(window).width()) {
			$(elem.parentNode).find('ul').css('position', 'absolute');
			$(elem.parentNode).find('ul').css('left',  (offsetLeft-menuWidth+elem.offsetWidth-3)+'px');
		}
		else if(offsetLeft - menuWidth < $(window).width()) {
			$(elem.parentNode).find('ul').css('position', 'absolute');
			$(elem.parentNode).find('ul').css('left',  offsetLeft+'px');
		}
		$('embed, object').css('visibility', 'hidden');
		if(isIE6()) $('select').css('visibility', 'hidden');

		$(elem.parentNode).find('ul').css('visibility', 'visible');
		$(elem.parentNode).addClass('over');
		$(elem).one('blur', function(event) {
			if(elem.parentNode.overmenu != true)
			{
				$(elem.parentNode).removeClass('over');
				$(elem.parentNode).find('ul').css('display', 'none');
				$('embed, object, select').css('visibility', 'visible');
			}
		});
		$(window).one('blur', function(event) {
			if(elem.parentNode.overmenu != true)
			{
				$(elem.parentNode).removeClass('over');
				$(elem.parentNode).find('ul').css('display', 'none');
				$('embed, object, select').css('visibility', 'visible');
			}
		});
		$(document).one('click', function(event) {
			if(elem.parentNode.overmenu != true)
			{
				$(elem.parentNode).removeClass('over');
				$(elem.parentNode).find('ul').css('display', 'none');
				$('embed, object, select').css('visibility', 'visible');
			}
		});
		return false;
	});
	$('#header_menu ul li ul li').mouseover(function() {
		this.parentNode.parentNode.overmenu = true;
		this.onmouseout = function(e) { this.parentNode.parentNode.overmenu = false;}
	});
	$('#header_menu ul li ul li').click(function() {
		$(this.parentNode).hide();
		this.parentNode.parentNode.className = 'dropdown';
	});
});

function isObject(o) { return (o && typeof o == 'object'); }
function isArray(o) { return (isObject(o) && o.constructor == Array); }


function isIE6()
{
	var browser=navigator.appName;
	var b_version=navigator.appVersion;
	var version=parseFloat(b_version);

	if(browser == 'Microsoft Internet Explorer' && version <= 6) {
		return true;
	}
}

function ValidateCustomFieldForm(NoFieldNameMsg, NoDefaultValueMsg, NoMultiValuesMessage)
{

	if (document.getElementById('FieldName') != null) {
		if ($('#FieldName').val() == '') {
			alert(NoFieldNameMsg);
			$('#FieldName').focus();
			return false;
		}
	}


	if ($('#DefaultValue').val() == '') {
		alert(NoDefaultValueMsg);
		$('#DefaultValue').focus();
		return false;
	}

	if (document.getElementById('MultiValues') != null) {


		var Values = $('#MultiValues').val().split('\n');

		if (Values.length > 0 && jQuery.trim($('#MultiValues').val()) != '') {
			for (var i=0; i<Values.length; i++) {
				var val = jQuery.trim(Values[i]);
				if (val != '') {
					var KeyField = document.createElement('INPUT');
					KeyField.type = 'hidden';
					KeyField.name = 'Key[' + i + ']';
					KeyField.value = val;

					var ValueField = document.createElement('INPUT');
					ValueField.type = 'hidden';
					ValueField.name = 'Value[' + i + ']';
					ValueField.value = val;

					document.getElementById('cfForm').appendChild(KeyField);
					document.getElementById('cfForm').appendChild(ValueField);
				}
			}
		} else {
			alert(NoMultiValuesMessage);
			$('#MultiValues').focus();
			return false;
		}
	}

	return true;
}