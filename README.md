# Trello Progress WP

## What's this?

Wordpress向けに、Trello API呼び出して複数のボード内のリストとカードを取得し、進捗状況を表示するプログラムです。

## [STEP1] Trello APIのキーの発行とtokenの取得

Trelloサーバーと認証するために、あなた自身が専用のキーを発行する必要があります。  
Trelloにログインした状態で以下のURLにアクセスし、Keyを発行します。  
また、同ページのリンク（APIキーのすぐ下にあるリンク）からtokenを取得します。  
これらはAPI発行時に毎回付与する必要がある情報です。  

https://trello.com/app-keyhttps://trello.com/app-key

![APIキーの発行とtokenの取得](https://user-images.githubusercontent.com/38904945/67149463-70c6fe00-f2e6-11e9-8661-d62876c75f12.png)

## [STEP2] Wordpressへ配置する

WordPressがインストールされているフォルダに、以下のディレクトリを作成します。

```
/wp-contents/myphp/
```

git cloneなどで取得した、以下のファイルを配置します。

```
progress-view.php
progress.php
```

### functon.phpへ追記する

取得したfunction.phpの中身をすべてコピーし、既存の使用中のテーマのfunction.phpに追記(貼り付け)します。  
そのまま配置するものではありません。

### progress-style.cssの適用

この作業は必須では有りません。  
しかし、progress-style.cssを適用することで、デフォルトのデザインが適用されます。  
trello progress wpはtableタグを使用しており、CSS3のborder-radiusの適用には若干コツがあります。  
そのため、CSS3の仕様と向き合うのが面倒であれば、これをそのまま適用して、カラーやマージンの調整などを行うのが近道かもしれません。  
  
cssの適用については、Wordpressの「外観」>「カスタマイズ」のメニューの「追加CSS」の設定にて追記することもできます。  
しかし、これでは全体に適用されてしまうため、おすすめはできません。  
できればページごとにCSSを設定できるようなプラグインを導入し、そこにCSSを設定することをおすすめします。  

## [STEP3] ショートコードで呼び出す

ショートコードにて、以下の形式で呼び出します。

```
[progress ユーザー名 APIキー トークン '1つ目のボードのshortUrl,2つ目のボードのshortUrl,...']
```

### ユーザー名

ログイン状態で表示される画面右上のアイコンをクリックするとユーザー名を確認できます。
![ユーザー名の確認](https://user-images.githubusercontent.com/38904945/67149473-79b7cf80-f2e6-11e9-95cc-6bac1ed9e0b4.png)

### 短縮URL

ボードの``shortUrl``は、カンマ区切り(間にスペースは入れない)で指定してください。
ボードの``shortUrl``とは、Trelloのボードにアクセスした際に表示されるURLの、ボード名の前のスラッシュ以降を含まないURLを指します。

例としては、以下のとおりです。

```
https://trello.com/b/PjmzAjhE/hogeland
 => https://trello.com/b/PjmzAjhE
```

## [STEP4] 進捗を上げましょう

上手く行けばSTEP3までで、進捗状況が公開されるでしょう。  
  
あとは自分自身との戦いです。  
検討を祈ります。  

## 動作仕様

* ショートコードからfunction.phpパラメータ（ユーザ名 APIキー token URLs）を受け渡します。
* progress.phpが、ボードのURLをもとに、TrelloのバッチAPIにて１つ以上のボードの情報を取得します
* ボード情報に含まれるボードIDより、全てのリストの一覧を取得し、ボード情報に関連付けて設定します
* ボード情報に含まれるボードIDより、全てのカードの一覧を取得し、ボード情報内のリストに関連付けて設定します
* progerss.phpで取得した情報をもとに、progress-view.phpにてHTMLを構成し、Wordressに返却します

## Lisence

PDS (Public Domain Software)  

誰でも自由に使用、変更、および商用化できます。  
著作表記も必要ありません。  
  
ただし、元の著作者であるhogeizmは、本ソフトウェア、および派生するソフトウェアについて一切の責任を負わず、保証もしません。  
  
Have a good your life!
