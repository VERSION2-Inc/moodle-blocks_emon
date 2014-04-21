/**
 * 共通変数
 */
var questionId = undefined;
var isMenuFlag = false;
var e = undefined;

var cmid = undefined;
var pageNumber = undefined;

var questionNumber = undefined;
var topCreateLayer = 20;
var prevScrollTop = undefined;

var validate = undefined;

// POST送信ライブラリ (フォーム名(DOM), 結果更新エレメント, コールバック関数, 確認メッセージ, 完了メッセージ)
function postForm(formname, element, callback) {
	var postData = {};
	$(formname + ' :input')
			.each(
					function() {
						if ($(this).attr('type') == 'checkbox') {
							postData[$(this).attr("name")] = $(this).attr(
									'checked') ? $(this).val() : '';
						} else if ($(this).attr('type') == 'radio') {
							if ($(this).attr('checked')) {
								postData[$(this).attr("name")] = $(this).val();
							}
						} else {
							postData[$(this).attr("name")] = $(this).val();
						}
					});

	var isSuccess = false;
	$.ajax({
		type : 'get',
		url : $(formname).attr('action'),
		data : postData,
		cache : false,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			isSuccess = true;
			try {
				json = $.parseJSON(result);
			} catch (e) {
				json = {
					'html' : result
				};
			}

			if (json.error) {
				alert(json.error);
			} else {
				if (element && json.html) {
					$(element).html(json.html);
				}
				if (callback) {
					callback(json.callback);
				}
			}
			return false;
		},
		complete : function() {
			if (!isSuccess) {
				alert('データ登録中にエラーが発生しました。内容を確認して再登録してください。');
			}
			loading(true);
		},
		statusCode : {
			205 : function() {
				window.location.reload();
			}
		}
	});
};

/**
 * ページ内の問題一覧を取得
 */
function getMoodlePageForEdit(cmid, pageNumber) {
	window.cmid = cmid;
	window.pageNumber = pageNumber;

	// ページ内の問題一覧を取得
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_get_page.php',
		data : 'cmid=' + cmid + '&page_number=' + pageNumber,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			$('#questions').html(result);
			$('.a_insert').click(function(e) {
				openQuestionForm(e, cmid, pageNumber, 0);
				return false;
			});
			$('.a_remove').click(function(e) {
				removeQuestion(cmid, pageNumber);
				return false;
			});
			$('.td_question').click(
					function(e) {
						openQuestionMenu(e, $(this).attr('question_id'),
								$(this).attr('question_number'));
						return false;
					});
			$('.question').hover(function() {
				$(this).children('table').addClass('question_mouseover');
			}, function() {
				$(this).children('table').removeClass('question_mouseover');
			});
			$('#question_sort').sortable(
					{
						stop : function() {
							setMoveQuestions(cmid, pageNumber, $(this)
									.sortable('serialize'));
						}
					});
			$('.question_drag').draggable({
				revert : true
			});
		},
		complete : function(result) {
			loading(true);
		}
	});

	// ページ一覧
	getMoodlePages(cmid, pageNumber);
}

function moveSort(cmid, pageNumber, questionId, mode) {
	// ページ一覧を取得
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_move_question.php',
		data : 'cmid=' + cmid + '&page_number=' + pageNumber + '&mode=' + mode
				+ '&questionid=' + questionId + '&'
				+ $('#question_sort').sortable('serialize'),
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			getMoodlePageForEdit(cmid, pageNumber);
		},
		complete : function(result) {
			loading(true);
		}
	});
}

function setMoveQuestions(cmid, pageNumber, questionIds) {
	// ページ一覧を取得
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_move_question.php',
		data : 'cmid=' + cmid + '&page_number=' + pageNumber + '&'
				+ questionIds,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			getMoodlePageForEdit(cmid, pageNumber);
		},
		complete : function(result) {
			loading(true);
		}
	});
}

function getMoodlePages(cmid, pageNumber) {
	// ページ一覧を取得
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_get_page_list.php',
		data : 'cmid=' + cmid + '&page_number=' + pageNumber,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			$('#pages').html(result);
			$('#page_sort').sortable(
					{
						stop : function() {
							setMovePages(cmid, pageNumber, $(this).sortable(
									'serialize'));
						}
					});
			$('.pageblock')
					.droppable(
							{
								accept : '.question_drag',
								drop : function(e, ui) {
									var questionId = ui.draggable.attr('id')
											.split('_')[2];
									var targetPageNumber = $(this).attr('id')
											.split('_')[2];
									movePageQuestion(cmid, pageNumber,
											targetPageNumber, questionId);
								}
							});
		},
		complete : function(result) {
			loading(true);
		}
	});
}

function movePageQuestion(cmid, pageNumber, targetPageNumber, questionId) {
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_move_page_question.php',
		data : 'cmid=' + cmid + '&page_number=' + pageNumber
				+ '&target_page_number=' + targetPageNumber + '&questionid='
				+ questionId,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			getMoodlePageForEdit(cmid, pageNumber);
			getMoodlePages(cmid, pageNumber);
		},
		complete : function(result) {
			loading(true);
		}
	});
}

function setMovePages(cmid, pageNumber, pageNumbers) {
	// ページ一覧を取得
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_move_page.php',
		data : 'cmid=' + cmid + '&' + pageNumbers,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			getMoodlePages(cmid, pageNumber);
		},
		complete : function(result) {
			loading(true);
		}
	});
}

// 問題編集メニューを開く
function openQuestionMenu(e, questionId, questionNumber) {
	if (!window.isMenuFlag) {
		window.questionId = questionId;
		window.questionNumber = questionNumber;
		window.isMenuFlag = true;

		$('#question_menu').css('display', '');
		$('#question_menu').css('left', (e.pageX - 30) + 'px');
		$('#question_menu').css('top', (e.pageY - 20) + 'px');
	} else {
		closeQuestionMenu();
	}
}

// 問題を登録する
function sendQuestionForm(obj) {
	// 得点の数字チェック
	if ($('input[name=defaultgrade]').length
			&& convertNumeric($('input[name=defaultgrade]').val()).match(
					/[^0-9]/g)) {
		alert('得点には半角数字のみ利用できます。');
		return false;
	}
	// 各プラグイン別のバリデート
	if (!validate()) {
		return false;
	} else {
		// tinyMCEからテキスト抽出
		if (tinyMCE && $('#questiontext').length) {
			if (tinyMCE.get('questiontext')) {
				tinyMCE.get('questiontext').hide();
				$('#questiontext').css('display', 'none');
			}
		}
		if (tinyMCE) {
			if (tinyMCE.get('filltext')) {
				tinyMCE.get('filltext').hide();
				$('#filltext').css('display', 'none');
			}
		}

		postForm(obj, '#create_layer', closeQuestionForm);

		return true;
	}
}

// 問題挿入用フォームを開く
function openQuestionInsertForm(e) {
	window.questionId = undefined;
	openQuestionForm(e);
}

// 問題新規作成フォームを開く
function openQuestionForm(e, cmid, pageNumber, questionNumber) {
	// レイヤーの調整
	$('#create_layer').html('');

	// 編集メニューを開いたときのスクロール座標
	window.prevScrollTop = (document.body.scrollTop || document.documentElement.scrollTop);

	// URLの設定
	if (!cmid) {
		cmid = window.cmid;
	}
	if (!pageNumber) {
		pageNumber = window.pageNumber;
	}
	if (questionNumber == undefined) {
		questionNumber = window.questionNumber;
	}

	var param = 'action=get_question_form&cmid=' + cmid + '&page_number='
			+ pageNumber;
	var useLoading = false;

	if (window.questionId) {
		// 問題IDが設定されている場合は修正フォーム
		moveQuestionForm();
		param += '&questionid=' + window.questionId + '&modify=1';
		useLoading = true;
	} else {
		// 新規で作成
		$('#create_layer').css('width', '');
		$('#create_layer').css('height', '5em');
		if (e != undefined) {
			if (window.event) {
				if (!e)
					var e = window.event;
				if (!e.pageX)
					e.pageX = e.clientX
							+ (document.body.scrollLeft || document.documentElement.scrollLeft);
				if (!e.pageY)
					e.pageY = e.clientY
							+ (document.body.scrollTop || document.documentElement.scrollTop);
			}
			$('#create_layer').css('top', (e.pageY - 50) + 'px');
		} else {
			$('#create_layer').css('top', '50px');
			$('#create_layer').html('Now loading...');
		}
		useLoading = false;
	}

	if (questionNumber) {
		param += '&question_number=' + questionNumber;
	}

	closeQuestionMenu();

	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_get_question_form.php',
		data : param,
		beforeSend : function(XMLHttpRequest) {
			if (useLoading) {
				loading();
			}
		},
		success : function(result) {
			$('#create_layer').html(result);
			uploader.putUploader();
		},
		complete : function(result) {
			if (useLoading) {
				loading(true);
			}
		}
	});

	$('#create_layer').css('left', '30px');
	$('#create_layer').css('display', '');
}

// 問題編集メニューを閉じる
function closeQuestionMenu() {
	isMenuFlag = false;
	questionId = undefined;
	questionNumber = undefined;
	$('#question_menu').css('display', 'none');
}

function closeQuestionForm() {
	window.parent.location.reload();
	return;
	$('#create_layer').css('display', 'none');
	$('#create_layer').html('');
	getMoodlePageForEdit(window.cmid, window.pageNumber);
}

// 問題形式を変更
function changeQuestionType(cmid, pageNumber, qtype, questionNumber) {
	moveQuestionForm();
	$('#create_layer').html('Now loading...');
	$.ajax({
		type : 'get',
		cache : false,
		url : '../api/ajax_get_question_form.php',
		data : 'cmid=' + cmid + '&page_number=' + pageNumber + '&qtype='
				+ qtype + '&question_number=' + questionNumber,
		beforeSend : function(XMLHttpRequest) {
			loading();
		},
		success : function(result) {
			$('#create_layer').html(result);
			// アップローダーのセット
			var param = {};
			param['cmid'] = $('#upload').attr('cmid');
			param['qtype'] = $('#upload').attr('qtype');
			param['itemid'] = $('#itemid').val();
			uploader.putUploader('uploader', 'v2uploader', $('#uploader').attr(
					'session_name'), $('#uploader').attr('session_id'), param,
					'progress');
		},
		complete : function(result) {
			loading(true);
		}
	});
}
function moveQuestionForm() {
	$('#create_layer').css('width', '90%');
	$('#create_layer').css(
			'height',
			(parseInt(document.all ? document.documentElement.clientHeight
					: window.innerHeight) - 80)
					+ 'px');
	$('#create_layer')
			.css(
					'top',
					((document.body.scrollTop || document.documentElement.scrollTop) + topCreateLayer)
							+ 'px');
}

// 問題を削除
function removeQuestion(cmid, pageNumber) {
	if (confirm('削除してもよろしいですか？')) {
		$.ajax({
			type : 'get',
			cache : false,
			url : '../api/ajax_remove_question.php',
			data : 'cmid=' + window.cmid + '&page_number=' + window.pageNumber
					+ '&questionid=' + window.questionId,
			beforeSend : function(XMLHttpRequest) {
				//loading();
			},
			success : function(result) {
				//getMoodlePageForEdit(cmid, pageNumber);
				window.parent.location.reload();
			},
			complete : function(result) {
				//loading(true);
			}
		});
	}
}

// ドロップするページオブジェクトを登録する
function setPageDroppable(pageNumber) {
	Droppables.add('page_' + pageNumber, {
		onDrop : function(dragElement, dropElement) {
			setPageDrop(dragElement, dropElement);
		}
	});
}
// ページ上にドロップされた
function setPageDrop(idFrom, idTo) {
	if (idFrom.id.indexOf('question') > -1) {
		isPageMove = true;

		// 問題を移動する
		idFrom.style.display = 'none';

		// 問題の元ID
		var tmp = idFrom.id.split('_');
		var questionId = tmp[1];
		idFrom.style.display = 'none';

		// 移動先のページ
		tmp = idTo.id.split('_');
		var pageNumber = tmp[1];

		$.ajax({
			type : 'post',
			cache : false,
			url : 'plugin_moodle.php',
			data : 'action=plugin_moodle_ajax_page_question_move&cmid='
					+ window.cmid + '&page_number=' + window.pageNumber
					+ '&target_page_number=' + pageNumber + '&questionid='
					+ questionId,
			beforeSend : function(XMLHttpRequest) {
				loading();
			},
			success : function(result) {
				$('#questions').html(result);
				getMoodlePages(window.cmid, window.pageNumber);
			},
			complete : function(result) {
				loading(true);
			}
		});
	}
}

// スクロール時の処理
function _scrollCreateLayer() {
	if ($('#create_layer').css('display') != 'none') {
		if (prevScrollTop != undefined) {
			setScrollTop(prevScrollTop);
		}
		$('#create_layer').css(
				'top',
				window.topCreateLayer
						+ parseInt(document.body.scrollTop
								|| document.documentElement.scrollTop) + 'px');
	}
}
function setScrollTop(top) {
	var diff;
	diff = top
			- (document.body.scrollTop || document.documentElement.scrollTop);
	if (diff != 0) {
		window.scrollBy(0, diff);
	}
}

// スクロールチェック
if (document.all) {
	window.onscroll = function() {
		_scrollCreateLayer();
	};
} else {
	document.onscroll = function() {
		_scrollCreateLayer();
	};
}
