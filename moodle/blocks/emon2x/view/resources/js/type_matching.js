/**
 * JavaScript for Glexa Quiz
 * 
 * (C) 2010 VERSION2 INC.
 */
if (!window.matching) {
	window.matching = window.matching || {};
}

matching.number = 0;

// 入力チェック
matching.validate = function()
{
	returncode = true;
	count = 0;
	rightcount = 0;
	
	$('input.matchinginput').each(function() {
		var id = $(this).attr('id').split('_');
		var leftlen = $('#matching_input_left_' + id[3]).val().replace(/ /g, '').length;
		var rightlen = $('#matching_input_right_' + id[3]).val().replace(/ /g, '').length;
		
		if (leftlen > 0 && !rightlen) {
			alert('右側を入力してください。');
			returncode = false;
			return false; // break;
		} else {
			if (leftlen > 0 && rightlen > 0) {
				count++;
				rightcount++;
			}
			if (!leftlen && rightlen > 0) {
				rightcount++;
			}
		}
	});
	
	if ((count == 2 && rightcount >= 3) || count > 2 || !returncode) {
		return returncode;
	} else {
		alert('左側に2件以上、右側に3件以上入力してください。\n※右側の答えを2件のみにしたい場合は、3件目に全角スペースを入力してください。');
		return false;
	}
}

// マッチング問題のキーワード入力フォーム追加
// 
matching.addMatching = function(number, left, right)
{
	if (number == undefined) {
		number = matching.number + 1;
	}
	
	if (left == undefined) {
		left = '';
	}
	
	if (right == undefined) {
		right = '';
	}
	
	var html = '';
	html += number + ': ';
	html += '<input type="text" name="option[matchings][' + number + '][0]" class="matchinginput" id="matching_input_left_' + number + '" value="' + left + '" />';
	html += ' = ';
	html += '<input type="text" name="option[matchings][' + number + '][1]" id="matching_input_right_' + number + '" value="' + right + '" />';
	html += '<br />';
	
	$('#matchings').append(html);
	matching.number = number;
}

