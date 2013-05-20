/**
 * JavaScript for Glexa Quiz
 * 
 * (C) 2008 VERSION2 INC.
 */
if ( typeof(QuizTypeMultichoice) == 'undefined' ) QuizTypeMultichoice = function() {};
QuizTypeMultichoice.prototype = {
	
	multichoice_corrects:undefined,
	multichoice_mode:false,
	multichoice_points:undefined,
	prev_body:undefined,
	prev_mode:undefined,
	checklimit:undefined,
	
	initMultichoice:function() {
		this.multichoice_corrects = new Array();
		this.multichoice_points = new Array();
	},
	validate:function() {

		convert_point = convertHalfNumeric($('input[name="defaultgrade"]').val());
		
		//配点チェック
		if(convert_point == ""){
			alert('配点を入力してください。');
			$('input[name="defaultgrade"]').focus();
			return false;
		}else{
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
		
		// チェックがあるか確認
		for (var i=1; i < multichoice.multichoice_corrects.length; i++) {
			if (multichoice.multichoice_corrects[i]) {
				return true;
			}
		}
		
		alert('正解をチェックしてください。');
		return false;
	},
	// body:選択肢入力, mode:true->各選択肢毎の配点
	setMultichoices:function(body, mode) {
		var bodies=body.split("\n");
		if (this.prev_mode==mode && this.prev_body==body) {
			return true;
		}
		this.prev_body=body;
		
		// 配点配列
		if (this.multichoice_points==undefined) {
			this.multichoice_points = new Array();
		}
		
		// 解答データ保存テンポラリ
		if (this.multichoice_corrects==undefined) {
			this.multichoice_corrects = new Array();
		}
		
		// 配点モード
		if (mode!=undefined) {
			this.multichoice_mode=mode;
		}
		
		var lineDiv = document.createElement('div');
		lineDiv.id = 'choices';
		
		var answerTable;
		var answerTbody;

		lineDiv.appendChild(document.createTextNode('正解にチェックをつけてください。'));
		lineDiv.appendChild(document.createElement('br'));
		
		// チェックボックス配置
		var answerCount = 0;
		pointCount = 0;
		if (bodies.length>0) {
			for (var i=0; i<bodies.length; i++) {
				if (bodies[i].replace(/^\s+|\s+$/g, "")!='') {
					if (this.multichoice_mode) {
						// 選択肢毎の配点
						var answerTr = document.createElement('tr');
						
							var pointTd = document.createElement('td');
							pointTd.style.width = '3em';
								var pointInput;
								if (document.all) {
									pointInput = document.createElement('<input name="option[points]['+(i+1)+']" class="label"> ');
								} else {
									pointInput = document.createElement('input');
									pointInput.name='option[points]['+(i+1)+']">';
								}
								pointInput.id = 'points_'+(i+1);
								pointInput.value = (this.multichoice_points[(i+1)]?this.multichoice_points[(i+1)]:0);
								pointInput.style.width = '2em';
							pointTd.appendChild(pointInput);
							pointCount++;							
							
							var answerTd = document.createElement('td');
								var answerText = document.createTextNode(bodies[i]);
							answerTd.appendChild(answerText);
							
						answerTr.appendChild(pointTd);
						answerTr.appendChild(answerTd);
						
						answerTbody.appendChild(answerTr);
					} else {
						var correctLabel = document.createElement('label');
						
						var optionInput;
						if (document.all) {
							optionInput = document.createElement('<input name="option[corrects]['+(i+1)+']" class="label">');
						} else {
							optionInput = document.createElement('input');
							optionInput.name='option[corrects]['+(i+1)+']';
						}
						optionInput.type='checkbox';
						optionInput.id='correct_'+(i+1);
						optionInput.value='true';
						optionInput.onclick=(function(_i) { return function() { multichoice.setMultichoiceCorrect(_i+1,this); } }) (i);
						correctLabel.appendChild(optionInput);
						
						var bodyText=document.createTextNode(bodies[i]);
						correctLabel.appendChild(bodyText);
						
						lineDiv.appendChild(correctLabel);
						
						var br=document.createElement('br');
						lineDiv.appendChild(br);
					}
					
					var selectHidden;
					if (document.all) {
						selectHidden = document.createElement('<input name="option[select]['+(i+1)+']">');
					} else {
						selectHidden = document.createElement('input');
						selectHidden.name='option[select]['+(i+1)+']';
					}
					selectHidden.type='hidden';
					selectHidden.value=bodies[i];
					lineDiv.appendChild(selectHidden);

					answerCount++;
				}
			}
		}
		
		// テーブルを閉じて配点表示
		if (this.multichoice_mode) {
			answerTable.appendChild(answerTbody);
			lineDiv.appendChild(answerTable);
			$('#ispoint').css('display', 'none');
		} else {
			$('#ispoint').css('display', '');
		}
		
		$('#choices').html('');
		$('#choices').append(lineDiv);
		
		// チェック制限の個数を変更
		// this.setMultichoiceLimitOptions(answerCount);
		
		// 既解答をチェック
		for (var i=0;i<this.multichoice_corrects.length;i++) {
			if (this.multichoice_corrects[i]) {
				if ($('#correct_'+i).length) {
					$('#correct_'+i).attr('checked', true);
				}
			}
		}
	},
	setMultichoiceCorrect:function(number,obj) {
		if (this.multichoice_corrects==undefined) {
			this.multichoice_corrects = new Array();
		}
		if (obj==undefined || obj.checked) {
			this.multichoice_corrects[number] = true;
		} else {
			this.multichoice_corrects[number] = undefined;
		}
	},
	setMultichoicePointMode:function(mode) {
		this.setMultichoices($('#question_body').val(),mode);
		if (mode) {
			$('#isevaluate').css('display', 'none');
		} else {
			$('#isevaluate').css('display', '');
		}
	},
	setMultichoicePoint:function(number,value) {
		if (this.multichoice_points==undefined) {
			this.multichoice_points = new Array();
		}
		this.multichoice_points[number]=value;
	}
}
var multichoice = new QuizTypeMultichoice();