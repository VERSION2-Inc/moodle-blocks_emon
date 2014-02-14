e問つく郎 Moodleプラグイン
==================
本モジュールは広島修道大学のプロジェクトにて、同大学の資金を用いて制作されたモジュールです。
開発中のコードネームは「問題野郎(仮)」でした。

VERSION2社製のGlexaの一部のプログラムを抜き出し、moodleと連携を取るために開発されたそれぞれ別々なプログラム(moodleブロックとGlexa)を、 配布用に１つのブロックモジュールに収めた形になっております。(従って実際にプロジェクトにて運用されているプログラム構成と若干異なります)

そのためmoodleの必要環境だけでは動作しない場合がございますが予めご了承願います。
また本ソフトウェアは日本語版のみご用意しておりますので(moodleを日本語以外でご利用の場合、文字化けする可能性があります)、他の言語への対応は今後のバージョンアップにご期待ください。

ダウンロードは無料ですが，全ての環境での検証作業を終了しているわけではないので，若干動作しない環境があることをあらかじめご了承ください。

This module is a development of the "問題野郎" project funded by Hiroshima Shudo University, that has since been made into its own project.

It was created by packaging parts of code in Glexa (manufactured by VERSION2) as a Moodle plugin.

Please note that there is a chance this module will not work on some Moodle environment. Also, this module is only available in Japanese. Stay tuned to future versions for other language supports.

Download for free.


動作条件 Requirements
------

PHP5以上 (PHP5.2未満の場合、別途JSON拡張モジュールをインストールしてください。)
※ PHP5.3以上でtimezoneを設定していない場合、ワーニングが出る場合があります。

PHP5 or greater (For PHP 5.2 or less, you must also install the JSON extension)
※For PHP5.3 or greater, PHP may display a warning if you have not set the timezone

インストール方法 Installation
------
moodleの/blocksディレクトリ配下に emon ディレクトリをコピーします。
管理者でログイン後、通知メニューにアクセスするとインストールが完了します。

Copy the "emon" directory inside /blocks under your Moodle directory. 
Installation will be completed after you log in as an administrator and access the notification menu.

使い方 How to use
------
任意のコースの編集モードで「e問つく朗」ブロックを追加してください。
詳細な使い方はブロック追加後に表示されるPDFマニュアルをご覧ください。

In edit mode, add "e問つく朗" block to you course.
Refer to the PDF manual displayed inside the block for details.

対象moodleバージョン Targeted Moodle versions
------
Moodle 2.3, Moodle 2.4

GitHub上のブランチ Branches
------
* mdl_2.3 -> Moodle2.3用ブランチ
* mdl_2.4 -> Moodle2.4用ブランチ
* master -> Moodle2.5用ブランチ(開発中)

一度git cloneでリポジトリをクローンし、git checkout mdl_2.3(ブランチ名) で切り替えて下さい。

* mdl_2.3 -> Moodle2.3 branch 
* mdl_2.4 -> Moodle2.4 branch
* master -> Moodle2.5 branch (under development)

First clone the repository with "git clone", then "git checkout mdl_2.3(branch name)" to switch branches.

注意事項 Warning
------
本ソフトウェアに起因するいかなる問題等について弊社は責任を負いません。予めご了承ください。
本ソフトウェアのライセンスはmoodle上のライセンスに従います(GNU GPL v2)

We are not responsible for any problem caused by this software. 
This software follows the license policy of Moodle (GNU GPL v2)

