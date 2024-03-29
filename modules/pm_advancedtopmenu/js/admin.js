/*
http://kevin.vanzonneveld.net
+      original by: Philippe Jausions (http://pear.php.net/user/jausions)
+      original by: Aidan Lister (http://aidanlister.com/)
+ reimplemented by: Kankrelune (http://www.webfaktory.info/)
+      improved by: Brett Zamir (http://brett-zamir.me)
+      improved by: Scott Baker
+      improved by: Theriault
*/
function pm_version_compare(v1,v2,operator){this.php_js=this.php_js||{};this.php_js.ENV=this.php_js.ENV||{};var i=0,x=0,compare=0,vm={'dev':-6,'alpha':-5,'a':-5,'beta':-4,'b':-4,'RC':-3,'rc':-3,'#':-2,'p':1,'pl':1},prepVersion=function(v){v=(''+v).replace(/[_\-+]/g,'.');v=v.replace(/([^.\d]+)/g,'.$1.').replace(/\.{2,}/g,'.');return(!v.length?[-8]:v.split('.'));},numVersion=function(v){return!v?0:(isNaN(v)?vm[v]||-7:parseInt(v,10));};v1=prepVersion(v1);v2=prepVersion(v2);x=Math.max(v1.length,v2.length);for(i=0;i<x;i++){if(v1[i]==v2[i]){continue;}
v1[i]=numVersion(v1[i]);v2[i]=numVersion(v2[i]);if(v1[i]<v2[i]){compare=-1;break;}else if(v1[i]>v2[i]){compare=1;break;}}
if(!operator){return compare;}
switch(operator){case'>':case'gt':return(compare>0);case'>=':case'ge':return(compare>=0);case'<=':case'le':return(compare<=0);case'==':case'=':case'eq':return(compare===0);case'<>':case'!=':case'ne':return(compare!==0);case'':case'<':case'lt':return(compare<0);default:return null;}}

function showHide(e) {
	$(e).toggle(  );
}
function refreshPreview() {
	var iframe = document.getElementById('iframePreview');
	if (typeof(iframe) != 'undefined' && iframe != null) {
		var reloadUrl = iframe.src;
		iframe.src = reloadUrl;
	}
}

function showType(e) {
	var val = $(e).val();

	$(e).parent('form').children('.column_element').slideUp( 'fast', function() {
		if(val == '1') {
			$(e).parent('form').children('.add_cms').slideDown('fast');
		}
		else if(val =='2') {
			$(e).parent('form').children('.add_cms').children('select').val('');
			$(e).parent('form').children('.add_link').slideDown('fast');
		}
	});
}
function showMenuType(e,type) {
	var val = $(e).val();
	if(type == 'menu')
		var parent = $(e).parent('.margin-form').parent('#blocMenuForm');
	else if(type == 'column')
		var parent = $(e).parent('.margin-form').parent('#blocColumnForm');
	else if(type == 'element')
		var parent = $(e).parent('.margin-form').parent('#blocElementForm');
	$(parent).children('.menu_element').slideUp( 'fast', function() {
		if(val == '1') {
			$(parent).children('.add_cms, .add_title').slideDown('fast');
		}
		else if(val == '2') {
			$(parent).children('.add_link, .add_title').slideDown('fast');
		}
		else if(val =='3') {
			$(parent).children('.add_category, .add_title').slideDown('fast');
		}
		else if(val =='4') {
			$(parent).children('.add_manufacturer, .add_title').slideDown('fast');
		}
		else if(val =='5') {
			$(parent).children('.add_supplier, .add_title').slideDown('fast');
		}
		else if(val =='6') {
			$(parent).children('.add_title').slideDown('fast');
		}
		else if(val =='7') {
			$(parent).children('.add_link').slideDown('fast');
		}
	});
}
function showColumnSelect(e,selected) {
	var val = $(e).val();
	if(typeof(selected) != 'undefined') {
		$("#column_select").load(base_config_url, {actionColumn:'get_select_columns',id_menu: val,column_selected: selected});
	} else  {
		$("#column_select").load(base_config_url, {actionColumn:'get_select_columns',id_menu: val});
	}
}
function showColumnWrapSelect(e,selected) {
	var val = $(e).val();
	if(typeof(selected) != 'undefined') {
		$("#columnWrap_select").load(base_config_url, {actionColumn:'get_select_columnsWrap',id_menu: val,columnWrap_selected: selected});
	} else  {
		$("#columnWrap_select").load(base_config_url, {actionColumn:'get_select_columnsWrap',id_menu: val});
	}
}
function hideNextIfTrue(e) {

	var val = parseInt($(e).val());
	if(val) {
		$(e).parent('.margin-form').next('div').slideUp( 'fast');
	}else {
		$(e).parent('.margin-form').next('div').slideDown( 'fast');
	}
}
function showSpanIfChecked(e,idToShow) {

	var val = $(e).attr('checked');
	if(val) {
		$(idToShow).css( 'display','inline');
	}else {
		$(idToShow).hide();
	}
}
var queue = false;
var next = false;
function show_info(id,content) {
	if(queue){next = new Array(id,content);return;}
	queue = true;
	if($('#'+id).is("div") === false)
		$('body').append('<div id="'+id+'" class="info_screen ui-state-hover"></div>');
	else return
	$('#'+id).html(content);
	$('#'+id).slideDown('slow');

	setTimeout(function() { $('#'+id).slideUp('slow',function() {$('#'+id).remove();queue = false;if(next){show_info(next[0],next[1]);next = false;} }) },2000);
}
var oldorder;
function setOrderElement(table, row) {
	var rows = table.tBodies[0].rows;
	var order = objectToarray(rows,'id').join(',');
	if(oldorder != order) {
		oldorder = order;
		saveOrderElement();
	}
}
function saveOrderElement() {
	$.get(base_config_url, { columnElementsPosition: oldorder },function(data) {
		show_info('saveorder',data);
	});
}
function saveOrderColumnWrap(orderColumnWrap) {
	$.get(base_config_url, { columnWrapPosition: orderColumnWrap },function(data) {
		show_info('saveorder',data);
	});
}
function saveOrderColumn(orderColumn) {
	$.get(base_config_url, { columnPosition: orderColumn },function(data) {
		show_info('saveorder',data);
	});
}
function setUnclickable(e) {
	var val = $(e).attr('checked');
	if(val) {
		$('.adtmInputLink').val( '#');
	}else {
		$('.adtmInputLink').val( '');
	}
}
function saveOrderMenu(orderMenu) {
	$.get(base_config_url, { menuPosition: orderMenu },function(data) {
		show_info('saveorder',data);
	});
}
$(document).ready(function() {
	$('div#addons-rating-container p.dismiss a').click(function() {
		$('div#addons-rating-container').hide(500);
		$.ajax({type : "GET", url : window.location+'&dismissRating=1' });
		return false;
	});
	
	
	
	//Initialise the second table specifying a dragClass and an onDrop function that will display an alert
	if (pm_version_compare($().jquery, '1.3', '>')) {
		$("#wrapConfigTab, #wrapFormTab").tabs({cache:false});
	} else {
		$("#configTab, #formTab").tabs({cache:false});
	}
	$('.table_sort').tableDnD({

	onDragClass: 'myDragClass',
	onDrop: function(table, row) {
	    setOrderElement(table, row);
	},
	onDragStart: function(table, row) {
		 	var rows = table.tBodies[0].rows;
			oldorder = objectToarray(rows,'id').join(',');
	}
	});
	$( ".columnWrapSort" ).sortable({
		placeholder: "ui-state-highlight",
		delay: 300,
		handle : '.dragWrap',
		update: function(event, ui) {
			var orderColumn = $(this).sortable('toArray');
			saveOrderColumnWrap(orderColumn.join(','));
		}
	});
	$('.ajax_script_load').click(function() {
		$.ajax({
			type: "GET",
		    url: $(this).attr('href'),
		    dataType: "script"
		 });
		return false;
	});
});
function objectToarray (o,e) {
a = new Array;
for (var i=1; i<o.length; i++) {

			a.push(o[i][e]);
}
return a;
}

var dialogInline;
function openDialogInline(contentId,dialogWidth,dialogHeight,fitScreenHeight) {
	
	dialogInline = $(contentId).dialog({
		modal: true,
		width:dialogWidth,
		height:dialogHeight,
		fitHeight:(typeof(fitScreenHeight)!='undefined' && fitScreenHeight ? true:false),
		close: function(event, ui) {$('body').css('overflow','auto'); $(contentId).dialog("destroy");},
		open: function (event,ui) {$('body').css('overflow','hidden');$(this).css('width','93%');$(contentId).show();$(contentId).css('overflow','auto');}
	});
}

function closeDialogInline() {
	$(dialogInline).dialog("close");
}

$(function(){
	$("#tabsetMenu").buildMbTabset({
		stop:function(){
		saveOrderMenu($.mbTabset.mbTabsetArray.join(','));
	},
		sortable:true
	});

});
