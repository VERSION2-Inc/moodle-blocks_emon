// jQuery setup
$(function() {
	// 問題カテゴリ
	$('select[name="courseid"]').change(function() {
		getMoodleCourseOptions(this.value);
	});
	// 初期設定
	if ($('select[name="courseid"]').length) {
		getMoodleCourseOptions($('select[name="courseid"]').val());
	}
});

var isLoading = false;
var loadingCount = 0;
var _loadingOverlay = null;
var parameter = {}; // MEDIAのアップロード用

/**
 * コース内のセクションとカテゴリを取得
 */
function getMoodleCourseOptions(courseid) {
	if (!courseid) {
		alert('管理できるコースが見つかりませんでした。');
		window.close();
	}

	//$('select[name="section"]').attr('disabled', 'disabled');
	// セクションの取得
	
	$.ajax({
		type : 'get',
		url : 'api.php',
		data : 'action=get_course_sections&courseid=' + courseid,
		dataType : 'json',
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			setMoodleSections(result);
		},
		complete : function(result) {
			loading(true);
		}
	});
	
}

/**
 * セクションSELECTの設定
 */
function setMoodleSections(result) {
	$('select[name="section"]').children().remove();
	for (var number in result.sections) {
		$('select[name="section"]').append(
				$('<option value="' + number + '">' + number + '</option>'));
	}
	$('select[name="section"]').attr('enabled', '');
}

// 問題作成時のファイル一覧
function setMediaFile(filename) {
	$('#progress').html('');
	$('#media_files').html($('#media_files').html() + 'ファイル: <b>' + filename + '</b><br />');
	$('#question_files').val($('#question_files').val() + filename + '\n');
}

/**
 * 半角数字チェック(0-9の全角数字を半角にし、半角数字以外の文字を取り除く。)
 */
function convertHalfNumeric(src) {
	han = "0123456789";
	zen = "０１２３４５６７８９";
	convert_point = "";
	for (i = 0; i < src.length; i++) {
		c = src.charAt(i);
		n = zen.indexOf(c, 0);

		if (n >= 0)
			c = han.charAt(n);
		convert_point += c;
	}
	// 半角数字以外を取り除く。
	convert_point = convert_point.replace(/\D/g, '');
	if (!convert_point) {
		convert_point = 0;
	}
	return convert_point;
}

// 英数文字以外を全角数字から半角数字へ
function convertNumeric(src) {
	src = src.replace(/\D/g, '');
	return src.replace(/(\W)/g, function($0) {
		return String.fromCharCode($0.charCodeAt(0) - 65248);
	});
}

// SWFオブジェクトを取得
function _swf(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName]
	} else {
		return document[movieName]
	}
}

// ローディング表示をする
function loading(isRemove) {
	var id = 'loading_layer';
	var jid = '#' + id;

	isRemove = (isRemove == true) ? true : false;

	// 表示回数
	if (isRemove) {
		loadingCount--;
		if (loadingCount != 0) {
			return;
		}
	} else {
		loadingCount++;
	}

	if (isRemove && typeof _loadingOverlay != null) {
		_loadingOverlay.remove();
		_loadingOverlay = null
	} else if (_loadingOverlay == null) {
		var top = Math.floor(($(window).height() - $(jid).height()) / 2);
		var left = Math.floor(($(window).width() - $(jid).width()) / 2);

		$(jid).css('top', top);
		$(jid).css('left', left);
		try {
			_loadingOverlay = getBusyOverlay(
					document.body,
					{
						color : 'gray',
						opacity : 0.5,
						text : 'loading',
						style : 'text-decoration:blink;font-weight:bold;font-size:12px;color:white'
					}, {
						color : '#fff',
						size : 100,
						type : 'c'
					});
		} catch (e) {
		}
	}
}

/**
 * オブジェクト内でタイマー動作させるためのbind
 */
Function.prototype.bind = function(object) {
	var __method = this;
	var __object = object;
	var __arguments = arguments;
	for ( var index = 0; index < __arguments.length - 1; ++index)
		__arguments[index] = __artguments[index + 1];
	__arguments.length = __arguments.length - 1;
	return function() {
		return __method.apply(__object, __arguments);
	}
};

var filename = '';

/**
 * Javascript library for v2uploader
 * 
 * Copyright(c) 2008 VERSION2. All Rights Reserved.
 */
if (typeof (Uploader) == 'undefined')
	Uploader = function() {
	};
Uploader.prototype = {
	swf_element : undefined,
	swf_source : 'resources/bin/v2uploader.swf?isOne=1&inipath=resources/bin/&label=ファイルをアップロード',
	swf_width : '100%',
	swf_height : '24',
	swf_progressId : undefined,
	progressid : undefined,
	questionInstanceId : undefined,
	questionType : undefined,
	destid : undefined,
	sesskey : undefined,
	sessid : undefined,
	param : undefined,
	postimer : undefined,

	/**
	 * put uploader plugin on html
	 * 
	 * @param int
	 *            id (エレメントのユニークID)
	 * @param string
	 *            seeekey (PHP等のセッションキー)
	 * @param string
	 *            sessid (PHP等のセッション値)
	 * @param array
	 *            array
	 */
	putUploader : function(destid, id, sesskey, sessid, array, progressid) {
		var $iframe = $('<iframe scrolling="no" frameborder="0" width="240" height="30" />').load(function () {
			var $ibody = $iframe.contents().find('body').css('margin', 0).css('padding', 0);
			if ($ibody.find('form').length == 0) {
				$ibody.append([
					'<form action="api/ajax_upload.php" method="post" enctype="multipart/form-data">',
					'<input type="hidden" name="itemid" value="' + $('#itemid').val() + '" />',
					'<input type="file" name="Filedata" onchange="this.form.submit();" />',
					'</form>'
					].join(''));
			}
			$iframe.width($ibody.width()).height($ibody.height());
		});
		$('#view_uploader').html($iframe);
		return; // GOOD BY FLASH!
		
		var param = '';
		var html = '';
		var count = 1;
		var offset;
		var dimension;
		for ( var i in array) {
			param += '&pkey' + count + '=' + i + '&pval' + count + '='
					+ array[i];
			count++;
		}

		var html = '';
		if (navigator.appName.indexOf("Microsoft") != -1) {
			html = '<object id="'
					+ id
					+ '" width="'
					+ this.swf_width
					+ '" height="'
					+ this.swf_height
					+ '" align="top" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0">';
			html += '<param name="allowScriptAccess" value="sameDomain" />';
			html += '<param name="movie" value="' + this.swf_source
					+ '&sesskey=' + sesskey + '&sessid=' + sessid + '&id=' + id
					+ param + '" />';
			html += '<param name="quality" value="high" />';
			html += '<param name="wmode" value="transparent" />';
		}
		html += '<embed id="'
				+ id
				+ '" name="'
				+ id
				+ '" wmode="transparent" src="'
				+ this.swf_source
				+ '&sesskey='
				+ sesskey
				+ '&sessid='
				+ sessid
				+ '&id='
				+ id
				+ param
				+ '" quality="high" width="'
				+ this.swf_width
				+ '" height="'
				+ this.swf_height
				+ '" align="top" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
		if (navigator.appName.indexOf("Microsoft") != -1) {
			html += '</object>';
		}

		$('#' + destid).html(html);
		// タイマーで表示位置を調整
		clearTimeout(this.postimer);
		this.postimer = setTimeout(this.changePosition.bind(this), 500);

		this.destid = destid;
		this.previd = id;
		this.sesskey = sesskey;
		this.sessid = sessid;
		this.param = array;
		this.progressid = progressid;

		// 問題ID
		this.questionInstanceId = array['question_instance_id'];
		this.questionType = array['type'];
	},
	// アップローダーの位置を動的に調整
	changePosition : function() {
		clearTimeout(this.postimer);
		if ($('#view_uploader').length && $('#uploader').length) {
			$('#uploader').css('position', 'absolute');
			$('#uploader').css(
					'left',
					(parseInt($('#view_uploader').offset().left) - parseInt($(
							'#create_layer').position().left))
							+ 'px');
			$('#uploader')
					.css(
							'top',
							(parseInt($('#view_uploader').offset().top)
									+ parseInt($('#create_layer').scrollTop()) - parseInt($(
									'#create_layer').position().top))
									+ 'px');
			$('#uploader').width($('#view_uploader').width());
			$('#uploader').height($('#view_upload').height());
			this.postimer = setTimeout(this.changePosition.bind(this), 300);
		}
	},
	openDialog : function(id, progressid) {
		this.previd = id;
		this.progressid = progressid;
		_swf(id).openDialog();
	},
	uploadItems : function(id) {
		if (id == undefined) {
			id = this.previd;
		}
		_swf(id).uploadItems();
	},
	cancelItems : function() {
	},
	ExternalError : function(msg, url) {
		alert(msg);
	},
	ExternalAddFile : function(file) {
		// file.name
		window.filename = file;
	},
	ExternalOnAddFile : function() {
		this.uploadItems();
	},
	ExternalComplete : function(result) {
		setMediaFile(window.filename);
		this.putUploader(this.destid, this.previd, this.sesskey, this.sessid,
				this.param, this.progressid);
	},
	ExternalOnLoad : function(msg) {
	},
	ExternalProgress : function(value) {
		if (value > 0) {
			var html = '<span class="message">';
			for ( var i = 0; i < value; i = i + 10) {
				html += '■■';
			}
			html += '</span><span class="gray">';
			for ( var i = value; i < 100; i = i + 10) {
				html += '■■';
			}
			html += '</span>';
			$('#' + this.progressid).css('display', '');
			$('#' + this.progressid).html(html);
		} else {
			$('#' + this.progressid).css('display', 'none');
			$('#' + this.progressid).html('');
		}
	}
}
var uploader = new Uploader();
