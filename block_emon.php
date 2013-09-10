<?PHP 
class block_emon extends block_base {
    function init() {
        $this->title = get_string('emon', 'block_emon');
        //2013年5月13日,03版リリース
        //TODO: リリース日に差し替えること。
        //(Moodle対象バージョン)(年)(月)(日)(リリース回)
        $this->version = 2013051404;
    }

    function instance_allow_multiple() {
        return false;
    }
    
    function applicable_formats() {
        return array('all' => true, 'my' => false, 'tag' => false);
    }
    
    function get_content() {
        global $CFG, $COURSE;
        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }
	    if (!$context = get_context_instance(CONTEXT_COURSE, $COURSE->id)) {
	        print_error('nocontext');
	    }
	    
	    $url = $CFG->wwwroot . '/blocks/emon/';
	    
        $this->content = new stdClass;
        $this->content->footer = get_string('copyright', 'block_emon');
		
        // capability check
		if (has_capability('mod/quiz:manage', $context) || $COURSE->id == 1) {
			$this->content->text = '<form id="glexaform" action="' . $url . 'view/form.php" method="get" target="emon" onsubmit="window.open(\'about:blank\', \'emon\', \'width=1020,height=720,resizable=yes,scrollbars=yes\')">' .
					'<center><input type="submit" value="' . get_string('glexaedit', 'block_emon') . '" onclick="document.getElementById(\'glexacmid\')" value=""/></center>' .
					'<input type="hidden" name="course" value="' . $COURSE->id . '" />' .
					'<input type="hidden" id="glexacmid" name="cmid" value="" />' .
					'</form>';
			
			$this->content->text .= '<script type="text/javascript">' .
				// コース内のモジュールメニューにリンクを追加
				'var classes = document.getElementsByTagName("a");' . 
				'for (var i=0; i < classes.length; i++) {' .
				'  if (classes[i].className != "editing_assign") {continue;}' .
				// <li>タグからモジュールIDを取得
				'  var cmid = classes[i].parentNode.parentNode.parentNode.id.split("-")[1];' .
				'  var className = classes[i].parentNode.parentNode.parentNode.className;' .
				// リンクタグ生成
				'  if (className.indexOf("activity quiz") < 0) { continue; }' .
				'  var glink = document.createElement("a");' .
				'  var gstr = document.createTextNode("e");' .
				'  glink.href="#";' .
				// リンク押下時のonclick
				'  glink.onclick = function(_cmid) { ' .
				'    return function() {' .
				'      window.open("about:blank", "emon", "width=1020,height=720,resizable=yes,scrollbars=yes");' .
				'      document.getElementById("glexacmid").value = _cmid;' .
				'      document.getElementById("glexaform").submit();' .
				'      return false;' .
				'    }' .
				'   } (cmid);' . 
				'  glink.appendChild(gstr);' .
				// リンク追加 
				'  classes[i].parentNode.appendChild(glink);' .
				'}' .
				'' .
				'</script>';
			$this->content->text .= '<div style="text-align:center"><a href="' .$url.'docs/emon_teacher.pdf" target="_blank">' . get_string('glexa_manual', 'block_emon') . '</a></div>';
		}
		
        return $this->content;
    }
}

?>
