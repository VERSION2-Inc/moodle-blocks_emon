
// See. tinyMCEのプラグイン(glexaquizfill)
if ( typeof(QuizTypeFill) == 'undefined' ) QuizTypeFill = function() {};
QuizTypeFill.prototype = {
	fillCount:1,	// 現在の穴埋めの個数
	
	validate:function() {
		if (fill.fillCount<=1) {
			alert('穴埋めを設定してください。');
			return false;
		}
		
		// 選択問題の行数チェック
		for (var i = 1; i < fill.fillCount; i++) {
			if ($('#answers_' + i).length && $('#types_' + i).length) {
				if ($('#types_' + i).val() == 1) {
					if ($('#answers_' + i).val().split("\n").length < 2) {
						alert('多肢選択は２択以上を設定してください。');
					 	return false;
					}
				}
			}
		}
		
		
		//それぞれの配点をコンバート
		for (var i = 1; i < fill.fillCount; i++) {
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
		return true;
	},
	// INPUTタグ取得と調整用のフォーム生成(tinyMCEプラグインから呼び出される)
	setFillInputField:function(str,ed,type) {
		if (ed==undefined) {
			return false;
		}
		var len = str.length + 2;	// 2=表示幅調整
		if (len>30) {
			len=30;
		}
		var tag='<input type="text" id="fill_'+this.fillCount+'" style="width:'+parseInt(len)+'em;" type="' + type + '" value="'+this.fillCount+'.'+str+'" disabled="true" />';
		
		this.setFillAnswerLayer(str,type);
		
		return tag;
	},
	// 正解設定にフィールド追加 | type:0=入力,1=セレクト
	setFillAnswerLayer:function(str, type, point, usecase) {
		str = str.replace(/&#039;/g, "'");
		var html='';
		if ($('#answer_layer').length) {
			var lineDiv = document.createElement('div');
			lineDiv.id = 'line_'+this.fillCount;
			lineDiv.appendChild(document.createTextNode(this.fillCount+'.'));
			
			var typeInput;
				if (document.all) {
					typeInput = document.createElement('<input name="option[types]['+this.fillCount+']">');
				} else {
					typeInput = document.createElement('input');
					typeInput.name='option[types]['+this.fillCount+']';
				}
				typeInput.type = 'hidden';
				typeInput.id = 'types_'+this.fillCount;
				typeInput.value = (parseInt(type)>0)?1:0;
			lineDiv.appendChild(typeInput);
			
			if (parseInt(type)>0) {
				// セレクト
				var answerSelect;
					if (document.all) {
						answerSelect = document.createElement('<textarea name="option[answers]['+this.fillCount+']">');
					} else {
						answerSelect = document.createElement('textarea');
						answerSelect.name='option[answers]['+this.fillCount+']';
					}
					answerSelect.id = 'answers_'+this.fillCount;
					answerSelect.value = str;
					answerSelect.style.width = '20em';
					answerSelect.style.height = '4em';
				lineDiv.appendChild(answerSelect);
			} else {
				// 入力
				var answerInput;
					if (document.all) {
						answerInput = document.createElement('<input name="option[answers]['+this.fillCount+']">');
					} else {
						answerInput = document.createElement('input');
						answerInput.name='option[answers]['+this.fillCount+']';
					}
					answerInput.type = 'text';
					answerInput.id = 'answers_'+this.fillCount;
					answerInput.value = str;
					answerInput.style.width = '20em';
				lineDiv.appendChild(answerInput);
				
				// チェックで大文字・小文字を区別
				var optionInput;
					if (document.all) {
						optionInput = document.createElement('<input name="option[usecases]['+this.fillCount+']">');
					} else {
						optionInput = document.createElement('input');
						optionInput.name='option[usecases]['+this.fillCount+']';
					}
					optionInput.type='checkbox';
					optionInput.id='usecase_'+this.fillCount;
					optionInput.value='1';
				lineDiv.appendChild(optionInput);
				lineDiv.appendChild(document.createTextNode('大文字・小文字を区別'));
			}
			
			var pointInput;
				if (document.all) {
					pointInput = document.createElement('<input name="option[points]['+this.fillCount+']">');
				} else {
					pointInput = document.createElement('input');
					pointInput.name='option[points]['+this.fillCount+']';
				}
				pointInput.type = 'text';
				pointInput.id = 'points_'+this.fillCount;
				if (point!=undefined) {
					pointInput.value = point;
				} else {
					pointInput.value = 1;
				}
				pointInput.style.width = '2em';
			lineDiv.appendChild(document.createTextNode(' '));
			lineDiv.appendChild(pointInput);
			lineDiv.appendChild(document.createTextNode('点'));
			
			var removeButton = document.createElement('input');
				removeButton.type = 'button';
				removeButton.className = 'submit';
				removeButton.value = '解除';
				removeButton.onclick = (function(_fillCount) { return function() { fill.removeFillInputField(_fillCount); } }) (this.fillCount);
			lineDiv.appendChild(removeButton);
			
			$('#answer_layer').append(lineDiv);
			
			if (usecase && usecase == 1) {
				$('#usecase_'+this.fillCount).attr('checked', 'checked');
			}
			
			this.fillCount++;
		}
	},
	// フィールド削除
	removeFillInputField:function(number) {
		if (!tinyMCE.get('filltext')) {
			setTimeout(this.removeFillInputField.bind(this), 300);
			return false;
		}
		var objdoc=tinyMCE.get('filltext').getDoc();
		var str,reg;
		str = '<input id=("|)fill_'+number+'("|)[^>]*>';
		reg=new RegExp(str, 'i');
		objdoc.body.innerHTML = objdoc.body.innerHTML.replace(reg, $('#answers_'+number).val());
		//$('#answer_layer').remove($('#line_'+number));
		$('#line_'+number).remove();
		this.sortFill();
	},
	
	// 正解とINPUTをソート
	sortFill:function() {
		if (!tinyMCE.get('filltext')) {
			setTimeout(this.sortFill.bind(this), 500);
			return false;
		}
		var objdoc=tinyMCE.get('filltext').getDoc();
		var objall;
		var objtext,objpoint;
		var tmp;
		var tmptexts=new Array();
		var tmppoints=new Array();
		var tmptypes=new Array();
		var tmpcases=new Array();
		
		// IEとその他のブラウザ
		if (document.all && typeof objdoc.all=='unknown') {
			setTimeout(this.sortFill.bind(this), 500);
			return false;
		}
		
		if (objdoc.all) {
			objall=objdoc.all.tags('input');
		} else {
			objall=objdoc.getElementsByTagName('input');
		}
		
		//MCEからバッファへ順番を保存
		var count=1;
		for (i=0;i<objall.length; i++) {
			if (objall[i].id.match(/fill_/)) {
				//INPUTタグのIDから連番を取得する
				var number = parseInt(objall[i].id.replace(/fill_/, ''));
				if ($('#answers_'+number).length && $('#points_'+number).length && $('#types_'+number).length) {
					tmptexts[count] = $('#answers_'+number).val();
					tmppoints[count] = $('#points_'+number).val();
					tmptypes[count] = $('#types_'+number).val();
					if ($('#usecase_'+number).length) {
						tmpcases[count] = $('#usecase_'+number).attr('checked');
					} else {
						tmpcases[count] = undefined;
					}
				}
				//INPUTタグのIDを順番に書き換える
				objall[i].id = 'fill_'+count;
				if (tmptexts[count]!=undefined && tmptexts[count]!=undefined && tmppoints[count]!=undefined) {
					count++;
				}
			}
		}
		//一旦現在の正解情報を削除する
		$('#answer_layer').html('');
		
		//バッファから正解情報をセットする
		this.fillCount = 1;
		for (i=1;i<count;i++) {
			this.setFillAnswerLayer(tmptexts[i], tmptypes[i], tmppoints[i], tmpcases[i]);
			objdoc.getElementById('fill_'+i).value = i+'.'+tmptexts[i];
			objdoc.getElementById('fill_'+i).width = (tmptexts[i].length+2)+'em';
		}
	}
}

var fill = new QuizTypeFill();