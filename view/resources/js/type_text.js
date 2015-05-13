/**
 * JavaScript for Glexa Quiz
 * 
 * (C) 2008 VERSION2 INC.
 */
if ( typeof(QuizTypeText) == 'undefined' ) QuizTypeText = function() {};
QuizTypeText.prototype = {
	
	text_answer_number:1,
	
	validate:function() {
		// 正解キーワードのチェック
		var isCorrect = false;
		for (var i = 1; i < text.text_answer_number; i++) {
			if ($('#answers_' + i).val()) {
				isCorrect = true;
				break;
			}
		}
		if (!isCorrect) {
			alert('正解キーワードを設定してください。');
			return false;
		}
		
		//配点をコンバート
		for (var i = 1; i <= text.text_answer_number; i++) {
			if ($('#points_'+i).length) {
				$('#points_'+i).val(convertHalfNumeric($('#points_'+i).val()));

				//配点入力チェック
				if($('#points_'+i).val() == ""){
					alert('配点を入力してください。');
					$('#points_'+i).focus();
					return false;			
				}
			} else {
				break;
			}
		}
		text.text_answer_number=0;
		return true;
	},
	initText:function(num) {
		this.text_answer_number = num;
		if (num == 1) {
			this.addTextAnswer();
		}
	},
	// number:正解キーワード番号, answer:正解キーワード, point:配点 : それぞれ空で自動生成
	addTextAnswer:function(number,answer,point) {
		if (!number) {
			number=this.text_answer_number;
		}
		if (answer==undefined) {
			answer='';
		}
		
		var lineDiv = document.createElement('div');		
			var answerInput;
			if (document.all) {
				answerInput = document.createElement('<input name="option[answer]['+number+']">');
			} else {
				answerInput = document.createElement('input');
				answerInput.name='option[answer]['+number+']';
			}
			answerInput.type = 'text';
			answerInput.id = 'answers_'+number;
			answerInput.value = answer;
			answerInput.style.width = '36em';
		lineDiv.appendChild(answerInput);
		lineDiv.appendChild(document.createTextNode(' '));
		
		$('#answer_layer').append(lineDiv);
		this.text_answer_number++;

	}
}
var text = new QuizTypeText();
