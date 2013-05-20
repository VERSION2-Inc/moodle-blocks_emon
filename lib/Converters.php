<?php
$converters = new Converters();

class Converters
{
	function alert()
	{
		echo 'here';
		
	}

	/**
	 * 多肢選択(単一)のフォーム入力→API用変数変換
	 * 
	 * 
	 */
	function convertChoiceFormToAPI(&$params, $option)
	{
		// 選択肢
		$number = 0;
		foreach ($option['select'] as $s) {
			$params['answer'][$number] = $s;
			$number++;
		}
		$params['correct'] = $option['correct'] - 1;
		$params['shuffleanswers'] = $option['shuffleanswers'];
		
		return true;
	}
	
	/**
	 * 多肢選択(単一)のAPI用変数→フォーム入力変換
	 * 
	 * @param	&$question
	 * @return	bool
	 */
	function convertChoiceAPIToForms(&$params)
	{
		// 解答
		$questionBody = '';
		$correctNumber = 0;
		if (isset($params['options']['answers']) && is_array($params['options']['answers'])) {
			$number = 1;
			foreach ($params['options']['answers'] as $answer) {
				$questionBody .= trim($answer['answer']) . "\n"; 
				if ($answer['fraction'] == 1) {
					// 正解
					$correctNumber = $number;
				}
				$number++;
			}
		}
		$params['question_body'] = $questionBody;
		$params['option']['correct'] = $correctNumber;
		
		// オプション
		if (isset($params['options']['shuffleanswers'])) {
			$params['option']['shuffleanswers'] = $params['options']['shuffleanswers'];
		}
		
		return $params;
	}
	

	/**
	 * 多肢選択(複数)のフォーム入力→API用変数変換
	 * 
	 * 
	 */
	function convertMultichoiceFormToAPI(&$params, $option)
	{
		// 選択肢
		$number = 0;
		foreach ($option['select'] as $s) {
			$params['answer'][$number] = $s;
			$number++;
		}
		$params['corrects'] = $option['corrects'];
		$params['shuffleanswers'] = $option['shuffleanswers'];
		
		return true;
	}	
	
	/**
	 * 多肢選択(複数)のAPI用変数→フォーム入力変換
	 * 
	 * @param	&$question
	 * @return	bool
	 */
	function convertMultichoiceAPIToForms(&$params)
	{
		// 解答
		$questionBody = '';
		$corrects = array();
		if (isset($params['options']['answers']) && is_array($params['options']['answers'])) {
			$number = 1;
			foreach ($params['options']['answers'] as $answer) {
				$questionBody .= trim($answer['answer']) . "\n"; 
				if ($answer['fraction'] > 0) {
					// 正解
					$corrects[$number] = true;
				}
				$number++;
			}
		}
		$params['question_body'] = $questionBody;
		$params['option']['corrects'] = $corrects;
		
		// オプション
		if (isset($params['options']['shuffleanswers'])) {
			$params['option']['shuffleanswers'] = $params['options']['shuffleanswers'];
		}
		
		return $params;
	}
	

	/**
	 * 記述のフォーム入力→API用変数変換
	 * 
	 * 
	 */
	function convertTextFormToAPI(&$params, $option)
	{
		if (is_array($option['answer'])) {
			foreach ($option['answer'] as $a) {
				if (trim($a)) {
					$params['answer'][] = trim($a);
				}
			}
		}
		$params['usecase'] = $option['usecase'];
		
		return true;
	}	
	
	/**
	 * 記述のAPI用変数→フォーム入力変換
	 * 
	 * @param	&$question
	 * @return	bool
	 */
	function convertTextAPIToForms(&$params)
	{
		// 解答
		$questionBody = '';
		$corrects = array();
		if (isset($params['options']['answers']) && is_array($params['options']['answers'])) {
			foreach ($params['options']['answers'] as $answer) {
				$params['option']['answers'][] = $answer;
			}
		}
		
		// オプション
		if (isset($params['options']['usecase'])) {
			$params['option']['usecase'] = $params['options']['usecase'];
		}
		
		return $params;
	}


	/**
	 * 穴埋めのフォーム入力→API用変数変換
	 * 
	 * 
	 */
	function convertFillFormToAPI(&$params, $option)
	{
		// 配列のソート
		ksort($option['answers'], SORT_NUMERIC);
		if (is_array($option['answers'])) {
			$questionNumber = 1;
			// 問題文の作成
			foreach ($option['answers'] as $k => $a) {
				if ($option['types'][$k] == 1) {
					// 多肢選択問題(multichoice)
					$answers = explode("\n", $a);
					$answers[0] = '=' . $answers[0];
					shuffle($answers);
					$answer = '';
					if (is_array($answers)) {
						foreach ($answers as $aa) {
							if (trim($aa)) {
								$answer .= trim($aa) . '~';
							}
						}
						$answer = rtrim($answer, '~');
					}
					$params['questions'][$questionNumber]['questiontext'] = sprintf('{%d:MC:%s}', $option['points'][$k], $answer);
				} else {
					// 記述問題(shortanswer)
					// 形式置換
					$a = str_replace('/ ', '@VERSION2SLASH@', $a);
					$a = str_replace('/', '~=', $a);
					$a = str_replace('@VERSION2SLASH@', '/', $a);
					if ($option['usecases'][$k] == 1) {
						$replaceBody = '{%d:SAC:=%s}';
					} else {
						$replaceBody = '{%d:SA:=%s}';
					}
					$params['questions'][$questionNumber]['questiontext'] = sprintf($replaceBody, $option['points'][$k], $a);
				}
				$questionNumber++;
			}
		}
		
		// 一旦タグに置換
		$sourcetag = '/<input id="fill_(\d+)" style="width:\s*(\d+)em;"[^>]*>/i';
		$desttag = '<!--[$1]-->';
		$params['questiontext'] = $params['questiontext'] . preg_replace($sourcetag, $desttag, $option['filltext']);
		
		// Moodleの穴埋め形式に置換
		foreach ($params['questions'] as $number => $q) {
			$params['questiontext'] = str_replace('<!--[' . $number . ']-->', $q['questiontext'], $params['questiontext']);
		}
		
		// 合計点
		$point = 0;
		foreach ($option['points'] as $p) {
			$point += $p;
		}
		
		$params['defaultgrade'] = $point;

		return true;
	}	
	
	/**
	 * 穴埋めのAPI用変数→フォーム入力変換
	 * 
	 * @param	&$question
	 * @return	bool
	 */
	function convertFillAPIToForms(&$params)
	{
		if (isset($params['questiontext'])) {
			$params['option']['filltext'] = $params['questiontext'];
		}
		if (isset($params['options']['questions']) && is_array($params['options']['questions'])) {
			foreach ($params['options']['questions'] as $k => $q) {
				$correct = '';
				foreach ($q['options']['answers'] as $number => $a) {
					$a['answer'] = str_replace('/', '/ ', $a['answer']);
					if ($a['fraction'] > 0) {
						if ($q['qtype'] == QUIZ_TYPE_TEXT) {
							$correct = $a['answer'] . "/" . $correct;
						} else {
							$correct = $a['answer'] . "\n" . $correct;
						}
						$viewAnswer = $a['answer'];
					} else {
						if ($q['qtype'] == QUIZ_TYPE_TEXT) {
							$correct .= $a['answer'] . "/";
						} else {
							$correct .= $a['answer'] . "\n";
						}
					}
				}
				$correct = rtrim($correct);
				$correct = rtrim($correct, '/');
				
				$params['option']['filltext'] = preg_replace('/\{\#' . $k . '\}/',
					sprintf('<input type="text" id="fill_%d" value="%d.%s" disabled="true" style="width:%dem;"/>', $k, $k, $viewAnswer, mb_strlen($viewAnswer) + 4), $params['option']['filltext']);
				$params['option']['answers'][$k] = $correct;
				$params['option']['points'][$k] = $q['defaultmark'];
				$params['option']['types'][$k] = $q['qtype'] == QUIZ_TYPE_MULTICHOICE ? 1 : 0;
				if ($q['qtype'] != QUIZ_TYPE_MULTICHOICE) {
					$params['option']['usecases'][$k] = $q['options']['usecase'] ? 1 : 0;
				}
			}
		}
		
		return $params;
	}
	
	/**
	 * ○×のフォーム入力→API用変数変換
	 * 
	 * 
	 */
	function convertTruefalseFormToAPI(&$params, $option)
	{
		// 選択肢
		$params['answer'][0] = '○';
		$params['answer'][1] = '×';
		$params['correct'] = 1 - (int)$option['correct'];
		$params['feedback'][0] = $option['feedbacktrue'];
		$params['feedback'][1] = $option['feedbackfalse'];
		
		return true;
	}
	
	/**
	 * ○×のAPI用変数→フォーム入力変換
	 * 
	 * @param	&$question
	 * @return	bool
	 */
	function convertTruefalseAPIToForms(&$params)
	{
		// 解答
		$questionBody = '';
		if (isset($params['options']['answers']) && is_array($params['options']['answers'])) {
			foreach ($params['options']['answers'] as $id => $answer) {
				if ($answer['fraction'] == 1 && ($params['options']['trueanswer'] == $id)) {
					$params['option']['correct'] = 0;
				}
				if ($answer['fraction'] == 1 && ($params['options']['falseanswer'] == $id)) {
					$params['option']['correct'] = 1;
				}
				$params['option'][($params['options']['trueanswer'] == $id ? 'feedbacktrue' : 'feedbackfalse')] = $answer['feedback'];
			}
		}
		
		return $params;
	}
	
	/**
	 * 組み合わせのフォーム入力→API用変数変換
	 * 
	 * 
	 */
	function convertMatchFormToAPI(&$params, $option)
	{
		// 選択肢
		$number = 0;
		foreach ($option['matchings'] as $m) {
			$params['subquestions'][$number] = $m[0];
			$params['subanswers'][$number] = $m[1];
			$number++;
		}
		$params['shuffleanswers'] = $option['shuffleanswers'];
		
		return true;
	}
	
	/**
	 * 組み合わせのAPI用変数→フォーム入力変換
	 * 
	 * @param	&$question
	 * @return	bool
	 */
	function convertMatchAPIToForms(&$params)
	{
		// 解答
		$questionBody = '';
		if (isset($params['options']['subquestions']) && is_array($params['options']['subquestions'])) {
			$number = 1;
			foreach ($params['options']['subquestions'] as $id => $subquestion) {
				$params['option']['matchings'][$number][0] = $subquestion['questiontext'];
				$params['option']['matchings'][$number][1] = $subquestion['answertext'];
				$number++;
			}
		}
		// オプション
		if (isset($params['options']['shuffleanswers'])) {
			$params['option']['shuffleanswers'] = $params['options']['shuffleanswers'];
		}
		
		return $params;
	}
	
}