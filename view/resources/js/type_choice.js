/**
 * JavaScript for Glexa Quiz
 * 
 * (C) 2008 VERSION2 INC.
 */
if ( typeof(QuizTypeChoice) == 'undefined' ) QuizTypeChoice = function() {};
QuizTypeChoice.prototype = {
	
	choice_correct:0,
	choice_mode:false,
	choice_points:undefined,
	prev_body:undefined,
	
	// 入力チェック
	validate:function() {
	
		//1つの配点だったら
		convert_point = convertHalfNumeric($('input[name="defaultgrade"]').val());
		
		//配点チェック
		if(convert_point == ""){
			$('input[name="defaultgrade"]').val(1);
		} else {
			$('input[name="defaultgrade"]').val(convert_point);
		}
		
		//選択肢の入力確認
		var questionBody = $('textarea[name="question_body"]').val();
		var bodies = questionBody.split('\n');
		var count = 0;
		for (var i = 0; i < bodies.length; i++) {
			if (bodies[i].replace(/\s*/g, '')!='') {
				count++;
			}
		}
		if (count < 2) {
			alert('選択肢は２つ以上設定してください。');
			$('textarea[name="question_body"]').focus();
			return false;
		}
		
		// 正解
		if (!$('input[name="\option\[correct\]"]:checked').val()) {
			alert('正解を選択してください。');
			return false;
		}
		
		return true;
	},
	
	// 選択問題の正解選択フォーム
	// body:選択肢入力, correct:正解
	setChoices:function(body, correct) {
		var bodies=body.split("\n");
		var html='';
		pointCount = 0;
		
		// 正解
		if (!this.choice_correct) {
			this.choice_correct=correct?correct:0;
		}
		
		// 配点配列
		if (this.choice_points==undefined) {
			this.choice_points = new Array();
		}
		
		// 解答データ保存テンポラリ
		if (correct!=undefined) {
			this.choice_correct=correct;
		}
		
		var lineDiv = document.createElement('div');
		lineDiv.id = 'choices';
		
		var answerTable;
		
		// テーブルの開始と採点メッセージ
		if (this.choice_mode) {
			// 各配点
			lineDiv.appendChild(document.createTextNode(plugin_quiz_js_edit_choice_003));
			lineDiv.appendChild(document.createElement('br'));
			answerTable = document.createElement('table');
			answerTable.style.width = '60%';
			answerTbody = document.createElement('tbody');
		}
		
		// 正解タグ
		if (bodies.length>0) {
			for (var i=0; i<bodies.length; i++) {
				if (bodies[i].replace(/^\s+|\s+$/g, "")!='') {
					var correctLabel = document.createElement('label');
					
					var optionInput;
					if (document.all) {
						optionInput = document.createElement('<input name="option[correct]"  class="label">');
					} else {
						optionInput = document.createElement('input');
						optionInput.name='option[correct]';
					}
					optionInput.type='radio';
					optionInput.id='correct_'+(i+1);
					optionInput.value=i+1;
					optionInput.onclick=(function(_i) { return function() { choice.setChoiceCorrect(_i+1); } }) (i);
					correctLabel.appendChild(optionInput);
					
					var bodyText=document.createTextNode(bodies[i]);
					correctLabel.appendChild(bodyText);
					
					lineDiv.appendChild(correctLabel);
					
					var br=document.createElement('br');
					lineDiv.appendChild(br);
					
					var selectHidden=document.createElement('input');
					selectHidden.type='hidden';
					selectHidden.name='option[select]['+(i+1)+']';
					selectHidden.value=bodies[i];
					lineDiv.appendChild(selectHidden);
				}
			}
		}
		
		// テーブルを閉じて配点表示
		if (this.choice_mode) {
			answerTable.appendChild(answerTbody);
			lineDiv.appendChild(answerTable);
		}
		
		$('#choices').html('');
		$('#choices').append(lineDiv);
		
		// 既解答をチェック
		if ($('#correct_'+this.choice_correct).length) {
			$('#correct_'+this.choice_correct).attr('checked', true);
		}
	},
	setChoiceCorrect:function(val) {
		this.choice_correct=val;
	},
	setChoicePointMode:function(mode) {
		this.setChoices($('#question_body').val(), this.choice_correct, mode);
	},
	setChoicePoint:function(number,value) {
		if (this.choice_points==undefined) {
			this.choice_points = new Array();
		}
		this.choice_points[number]=value;
	}
}
var choice = new QuizTypeChoice();
