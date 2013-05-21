<?php
$views = new ViewManager();

class ViewManager
{
	/**
	 * 選択問題を取得する
	 *
	 * @param	int		$questionId	問題ID
	 * @param	string	$instruction
	 * @param	string	$body	question_body
	 * @param	&array	$option	部品
	 * @return	string	HTML化された選択問題
	 */
	function getChoice($questionId, $instruction, &$options)
	{
		// 選択肢HTMLの設定
		$selects = array();
		if (is_array($options->options->answers)) {
			foreach ($options->options->answers as $k => $v) {
				$selects[] = array('number' => $k, 'select' => trim($v->answer));
			}
		}

		// HTMLを生成
		$html = $instruction;
		$html .= '<span class="message">評点のデフォルト値: ' . $options->defaultmark . ' |';
		$html .= ($options->options->shuffleanswers == '1') ? ' シャッフル' : ' 入力順';
		$html .= '</span><br />';

		foreach ($selects as $s) {
			// よこorたて
			// 正解に色をつける
			if ($options->options->answers[$s['number']]) {
				if ($options->options->answers[$s['number']]->fraction == 1){
					$s['select'] = sprintf('<span style="border:solid 1px #ff6600;padding:1px;margin:1px;">%s</span>', $s['select']);
				} else if ($options->options->answers[$s['number']]->fraction > 0) {
					$s['select'] = sprintf('<span style="border:solid 1px #fbaf5d;padding:1px;margin:1px;">%s</span>', $s['select']);
				}
			}
			
			$html .= sprintf('<label><input type="radio" id="answer_%d_%d" name="answer[%d]" value="%d" disabled="true"/> %s</label>',
						$questionId, $s['number'], $questionId, $s['number'], $s['select']);
			$html .= '<br />';
		}
		
		return $html;
	}

	/**
	 * 複数選択問題を取得する
	 *
	 * @param	int		$questionId	問題ID
	 * @param	string	$instruction
	 * @param	string	$body	question_body
	 * @param	&array	$option	部品
	 * @return	string	HTML化された選択問題
	 */
	function getMultichoice($questionId,$instruction, &$options)
	{
		global $glexalang;

		// 選択肢HTMLの設定
		if (isset($options->options->answers)) {
			foreach ($options->options->answers as $k => $v) {
				$selects[] = array('number' => $k, 'select' => trim($v->answer));
			}
		}

		$separator = '<br />';

		// HTMLを生成
		$html = $instruction;
		$html .= '<span class="message">評点のデフォルト値: ' . $options->defaultmark . ' |';
		$html .= ($options->options->shuffleanswers == '1') ? ' シャッフル' : ' 入力順';
		$html .= '</span><br />';

		//色分けの準備
		if (!empty($option->points)) {
			$points_sort = $option->points;
			rsort($points_sort);
			array_splice($points_sort, $option->checklimit);
		}

		foreach ($selects as $s) {
			if (true) {
				// 正解に色をつける
				if (isset($options->options->answers[$s['number']]->fraction) && $options->options->answers[$s['number']]->fraction > 0) {
					$s['select'] = sprintf('<span style="border:solid 1px #ff6600;padding:1px;margin:1px;">%s</span>', $s['select']);
				}
				$html .= sprintf('<label><input type="checkbox" id="answer_%d_%d" name="answer[%d][%d]" value="%d" %s disabled="true"/> %s</label>',$questionId, $s['number'], $questionId, $s['number'], $s['number'], ((isset($option->corrects[$s['number']]) && $option->corrects[$s['number']])?'checked="checked"':''), $s['select']);
				//$html .= sprintf('<div style="border:solid 1px %s;padding:1px;margin:1px;">%s</div>', $color, $s['select']);
			} else {
				// 正解に色をつける
				/*
				if ($option['points'][$s['number']]) {
					if ($points_sort[0] != $option['points'][$s['number']]){
						$s['select'] = sprintf('<span style="border:solid 1px #fbaf5d;padding:1px;margin:1px;">%s</span>', $s['select']);
					}else{
						$s['select'] = sprintf('<span style="border:solid 1px #ff6600;padding:1px;margin:1px;">%s</span>', $s['select']);
					}
				}
				$html .= sprintf('<label><input type="checkbox" id="answer_%d_%d" name="answer[%d][%d]" value="%d" %s disabled="true"/> %s</label>',
						$questionId, $s['number'], $questionId, $s['number'], $s['number'], (($option['points'][$s['number']])?'checked="checked"':''), $s['select']);
				*/
			}
			$html .= $separator;
		}

		return $html;
	}

	/**
	 * 入力問題を取得する
	 *
	 * @param	int		$questionId	問題ID
	 * @param	string	$instruction
	 * @param	string	$body	question_body
	 * @param	&array	$option	部品
	 * @return	string	HTML化された選択問題
	 */
	function getText($questionId, $instruction, &$options)
	{
		global $glexalang;

		// HTMLを生成
		$html = $instruction;
		$html .= '<span class="message">評点のデフォルト値: ' . $options->defaultmark . ' |';
		$html .= ($options->options->usecase == '1') ? ' 大文字小文字を区別する' : ' 大文字小文字を区別しない';
		$html .= '</span><br />';
		$size = sprintf('class="text-large"');
		$correct = '';
		if($options->options->answers){
			foreach ($options->options->answers as $a) {
				$correct .= $a->answer . ',';
			}
		}
		$html .= sprintf('<input type="text" %s value="%s" disabled="true"/>', $size, htmlspecialchars($correct));

		return $html;
	}

	/**
	 * 穴埋め問題を取得する
	 *
	 * @param	int		$questionId	問題ID
	 * @param	string	$instruction
	 * @param	string	$body	question_body
	 * @param	&array	$option	部品
	 * @return	string	HTML化された選択問題
	 */
	function getFill($questionId, $instruction, &$options)
	{
		global $glexalang;
		
		// HTMLを生成
		// 問題文表示を置換
		foreach ($options->options->questions as $k => $q) {
			if ($q->qtype == QUIZ_TYPE_MULTICHOICE) {
				$correct = array_shift($q->options->answers);
				$instruction = preg_replace('/\{\#' . $k . '\}/', sprintf('<select style="width:%dem;" disabled="true"><option>%s</option></select>', mb_strlen($correct->answer) + 4, $correct->answer), $instruction);
			} else {
				$correct = array_shift($q->options->answers);
				$instruction = preg_replace('/\{\#' . $k . '\}/', sprintf('<input type="text" style="width:$1em;" value="%s" disabled="true"/>', htmlspecialchars($correct->answer)), $instruction);
			}
		}
		$html = $instruction . '<br />';
		$html .= '<span class="message">評点のデフォルト値: ' . $options->defaultmark;
		$html .= '</span><br />';

		return $html;
	}

	/**
	 * ○×問題を取得する
	 *
	 * @param	int		$questionId	問題ID
	 * @param	string	$instruction
	 * @param	string	$body	question_body
	 * @param	&array	$option	部品
	 * @return	string	HTML化された選択問題
	 */
	function getTruefalse($questionId, $instruction, &$options)
	{
		// 正解の取得
		$correct = '';
		$feedback = '';
		if (is_array($options->options->answers)) {
			foreach ($options->options->answers as $k => $v) {
				if ($v->fraction > 0) {
					$correct = '正解: ' . $v->answer;
				}
				// フィードバック
				if ($v->feedback) {
					$feedback .= $v->answer . ': ' . $v->feedback . '<br />';
				}
			}
		}

		// HTMLを生成
		$html = $instruction;
		$html .= '<span class="message">評点のデフォルト値: ' . $options->defaultmark;
		$html .= '</span>';
		$html .= '<br />' . $correct;
		$html .= '<br />' . $feedback;
		
		return $html;
	}


	/**
	 * 組み合わせ問題を取得する
	 *
	 * @param	int		$questionId	問題ID
	 * @param	string	$instruction
	 * @param	string	$body	question_body
	 * @param	&array	$option	部品
	 * @return	string	HTML化された選択問題
	 */
	function getMatch($questionId, $instruction, &$options)
	{
    	// 選択肢HTMLの設定
    	$matchings = array();
    	if (is_array($options->options->subquestions)) {
    		$number = 1;
	    	foreach ($options->options->subquestions as $s) {
	    		$matchings[] = array('number' => $number++, 'left' => trim($s->questiontext), 'right' => trim($s->answertext));
	    		
			}
    	}
    	
		// HTMLを生成
		$html = $instruction;
		
		// 各種設定
		$html .= '<span class="message">評点のデフォルト値: ' . $options->defaultmark;
		if ($options->options->shuffleanswers == 1) {
			$html .= ' | シャッフル';
		}
		$html .= '</span><br />';
		
		foreach ($matchings as $number => $m) {
			$html .= '<span class="matchingbox">' . $m['left'] . '</span>';
			$html .= ' = ';
			$html .= '<span class="matchingbox">' . $m['right'] . '</span>';
			$html .= '<br />';
		}
		return $html;
	}
	
	/**
	 * 穴埋めの修正
	 *
	 * @param	int	$questionInstanceId
	 * @param	string	$instruction
	 * @param	array	&$option
	 * @return	$option
	 */
	function modifyChoice($questionInstanceId, $instruction, &$option)
	{
		// 解答を一旦ソート
		ksort($option['select']);

		// 解答フォームに展開
		foreach ($option['select'] as $k => $v) {
			$option['question_body'] .= trim($v) . "\n";
		}

		return $option;
	}


	/**
	 * 穴埋めの修正
	 *
	 * @param	int	$questionInstanceId
	 * @param	string	$instruction
	 * @param	array	&$option
	 * @return	$option
	 */
	function modifyFill($questionInstanceId, $instruction, &$option)
	{
		foreach ($option['answers'] as $k => $t) {
			$instruction = preg_replace('/\[\s*' . $k . '\s*\]<!--input:' . $k . ':(\d+)-->/',
				sprintf('<input type="text" id="fill_%d" value="%d.%s" disabled="true" style="width:$1em;"/>', $k, $k, htmlspecialchars($t)), $instruction);
		}
		$option['filltext'] = $instruction;

		// 正解をソート
		ksort($option['answers'], SORT_NUMERIC);
		return $option;
	}

}
