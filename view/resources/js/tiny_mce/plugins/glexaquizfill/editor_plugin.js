/**
 *	Quiz fill for glexa
 *  require plugin/js/quiz.js
 * 
 *  2008 VERSION2 INC.
 */

(function() {
	// 穴埋め入力
	tinymce.create('tinymce.plugins.glexaquizfill', {   
		init : function(ed, url) {   
			var t = this;
			t.editor = ed;
			ed.addCommand('glexaquizfill',
				function() {
					var s = ed.selection.getContent({format : 'text'});
					if (s.length>0) {
						ed.execCommand('mceReplaceContent', false, fill.setFillInputField(s,ed));
						fill.sortFill();
					}
				}
			);
			ed.addCommand('glexaquizselect',
				function() {
					var s = ed.selection.getContent({format : 'text'});
					if (s.length>0) {
						ed.execCommand('mceReplaceContent', false, fill.setFillInputField(s,ed,1));
						fill.sortFill();
					}
				}
			);
			ed.addButton('glexaquizfill', {title : '穴埋め入力', cmd : 'glexaquizfill'});   
			ed.addButton('glexaquizselect', {title : '穴埋め選択', cmd : 'glexaquizselect'});   
		}
	});   
	// プラグインの登録   
	tinymce.PluginManager.add('glexaquizfill', tinymce.plugins.glexaquizfill);   
})();  