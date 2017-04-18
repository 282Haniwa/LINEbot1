# LINEbot第１作目
## 作るに至った経緯
このボットはLINEbotを作るということとクラス設計の勉強、様々なAPIを触ることを目的として作った。

## 使ったAPI
Microsoft Computer Vision API  
Microsoft Translator API  
LINE bot API

## 注意
このPHPのコードは無料試用を前提に書かれているのでMicrosoftのAPIの有料サービスを利用する場合一部書き換える必要があるかもしれない(request uri 等)

## 機能説明
### 機能１
このBotに画像を投げるとMicrosoft Computer Vision APIに投げて画像の説明文を返してくれる。  
また、その説明文に対してMicrosoft Translator APIを通して日本語に翻訳してくれる。  
画像の中に人の顔があるとその人の年齢を返してくれる。  
~~（現在は１人分の顔年齢しか返してくれないが今後修正する予定である。）~~
修正済み

### 機能２
`@haniwa `の後に翻訳したい文字列を入力すると日本語なら英語に、英語なら日本語に翻訳してくれる。  
日本語を含んでいる場合は日本語として判断される。  
適当な文字列を入力した場合は正しく翻訳する手順を教えてくれる。  
