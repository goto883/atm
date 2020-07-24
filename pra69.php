<?php


/*
69 ATMを作成しよう
コマンドラインから実行すること
要件定義
・残額、入金、引き出しの機能を実装
実際にATMに必要な機能をリストアップして、ご自由に開発してみてください！

-------------
20200615
今回は、関数は使用せずに、クラスのメソッドだけで実装してみたいです。
Atmというクラスを作成し、ATMの機能は、すべてこのクラスに実装してみましょうか。
class Atm {
    public function main() {
        //メニュー選択
        //入力したメニューで、それぞれの機能を呼び出す
    }
}
$atm = new Atm;
$atm->main();
このようなイメージです。
関数の処理は、しっかりと書かれていると思いますので
まずはこのクラスのメソッドに移行してみましょうか。
bankbookクラスを用意していただいておりますが
ログイン機能は後に実装していただこうと思いますので
まずは基本的なATMの機能をAtmクラスに実装してみてください＾＾

修正項目
・classないに関数を移動

---------------
20200617
メニュー選択など、
定数で表現できるところは定数にしてみてください。

 if($process_check){
    echo '半角で指定の数字を入力してください。' . PHP_EOL;
    return $this->main($money);
}
再帰処理は、再帰するメソッド内で書きたいです。
でないと、その関数の処理はreturnで終了しますが、呼び出し元の処理が次に進んでしまい、
バグになることがあるからです。
mainメソッドの再帰をしたいなら、この処理はmainメソッドに書くべきです。
ですが、ここはProcessの再帰をさせたいですよね。

checkProcessというメソッド名ですが、
processCheckというメソッド名と似通っていて、わかりにくいかなと思いました。
またcheckProcessというメソッドにも関わらず、
処理は、入力したメニュー番号に該当する機能のメソッドを呼び出しており、
特にチェックしていないので、メソッド名としては、不自然ですかね
なので、メソッド名としては、selectMenuのようにメニュー選択を意味するメソッド名の方がベターと思います。

また
checkProcess内の
 echo '残高は' . $money . '円です。' . PHP_EOL ;
return $money;
残高照会もメソッドにしてみてください。
case 2:
    echo 'いくら入金しますか？';
    return $this->payMent($money);
    
できれば、echo もpayMentメソッド内に書きたいです。
$balanceはプロパティにもたせましょう。

public function reProcessStart($money){
    $re_process = $this->reProcess();
    $re_process_check = $this->reProcessCheck($re_process);
    if($re_process_check){
        echo '半角で指定の数字を入力してください。' . PHP_EOL;
        return $this->reProcessStart($money);
    }
    return $re_process;
}
引数で$moneyが渡ってきておりますが、
処理に使用していないようなので、不要ですかね。
このあたり修正してみてください＾＾

○修正項目
・定数で表せる箇所を定数に変更
・再起関数のメソッド変更
bankTransactionStartないで再起するように変更しました。
・checkProcessのメソッド名をselectMenuに変更
・selectMenu内にあったecho 'いくら入金しますか？'をpayMent内に移動
・selectMenu内にあったecho 'いくら引き出しますか？'をpayTransfer内に移動
・payMent、payTransfer、の変更後の金額を$this->balanceとプロパティに変更


20200620
//最初の残高を10000円でスタートさせる
$atm->main(10000);
mainメソッドの引数に、初期金額をわたしておりますが、
これですと、少しわかりにくいかなと思いますので、
class Atm{
    public $balance = 10000;//残高
とすればOKかと思います。
そうすれば、メソッド間で、引数に$moneyをわたさなくてもよさそうです。

このあたりで基本的なATMの機能はよさそうなので、
次はユーザーログイン機能を実装していきましょう。
Userクラスを新しく作成していただき、
そのプロパティにユーザーリストをもたせてみましょう。
class User {
    public $user_list = array(
        1 => array(
            "id" => "1",
            "password" => "1234",
            "name" => "tanaka",
            "balance" => "500000"
        ),
        2 => array(
            "id" => "2",
            "password" => "3456",
            "name" => "suzuki",
            "balance" => "1000000"
        )
    ); 
}
このような感じですかね。
それで、
Atmクラスのコンストラクタで
class Atm {
    public $user;
    public function __construct() {
        //ログイン
        $this->login();
    }
    public function login() {
        //id入力
        //Userクラスのユーザーリストにidがあるかチェック
        //なければエラー、再帰関数
        //Userクラスから指定されたユーザー取得
        //パスワード取得
        //取得したユーザーのパスワードと入力値が一致するかチェック
        //なければエラー、再帰関数
        //問題なければ、プロパティの$userにセット
    }
}
みたいな感じですかね。

修正箇所
・ATMの初期値$atm->main(10000);をclass Atmないの$balanceに初期値から値を持たせるように変更。付随する箇所も修正。
・userクラスの追加と付随する処理の追加


20200623
ログイン機能を実装されたのですね！
$this->user = new User;
今回はUserクラスをインスタンス化しなくてOKです。

idCheck メソッドは、Userクラスにもたせましょう。

$this->user = $this->user->user_list[$i];
また、パスワード認証前に、プロパティにuserをセットするのは、
やはり早いと思います。
passWordCheckStartで、すでにプロパティにセットされているUserのパスワードとチェックしていますが、
パスワードが一致しなくてもプロパティにuserがセットされつづけているのは
どこか違和感がございます。
passWordCheckStartで、パスワードも問題なければ、
返り値としてユーザー情報を返して、
最後にプロパティにユーザーをセットするという流れが自然かなと思いました。
標準入力系のメソッドがたくさんありますが、似通っているので
ひとつにまとめたいかなと思いました。

修正箇所
・Userクラスのインスタンス化を取りやめそれに伴う修正
・idCheckメソッドをUserクラスに移動それに伴う修正
・passwordのチェックuser_listの取得メソッドをUserクラスに作成
・入力のメソッド化

20200702
oginメソッドの
$this->user = new User;
こちらの処理は不要かと思います。

パスワードの入力ですが、
せっかくなので３回間違えたら、
プログラムを終了させるか、loginメソッドに再帰させるような処理を入れてみましょうか。

loginメソッドの閉じタグのインデントがずれているように見えます

それができましたら、
次は、各機能のバリデーションメソッドをそれぞれバリデーション用のクラスを宣言して、
そのクラスの中に書いていきましょうか。
例えば、メニュー選択のバリデーションは
validation/MenuValidation.php
というようなファイルを作成していただいて、クラスを宣言(バリデーション用のクラスとわかるようなクラス名にしてください）
そのクラスにcheckメソッドを追加
checkメソッドにメニュー選択のバリデーションを書くイメージです
※他の機能のバリデーションも同様ですね

ATMクラスの各機能のバリデーションは、それぞれ作成したバリデーションクラスのcheckメソッドを呼ぶようにしたいです。
すべてのバリデーションクラスには、checkメソッドを実装し、何のバリデーションかはクラス名から判別できます。

修正箇所
・$this->user = new User;を取り消しました。
・パスワードの回数チェックの追加
・インデントのズレ修正
・各バリデーション

20200707
パスワード最大間違い回数を実装していただきましたね！
$this->count === 3){
この3は定数にしたいです。
if($this->count <= 2){
この２も定数を使用して書きたいです。

バリデーションクラスを作成していただけましたね。
バリデーション用のメソッドの返り値は、
true or false にいたしましょう。

エラーメッセージは、ひとまず、バリデーションクラス内で出力してみましょうか。
全体的に、処理の流れがだいぶよくなってきました！

ファイルが多くなってきましたので、
githubにあげましょうか。
githubで確認するようにいたします。

修正箇所
・countの値をコンスト値に修正しました。
const COUNTMIN = 2;
const COUNTMAX = 3;
・バリデーションの返り値をtrue,falseに修正


20200712
githubにあげていただきましたね＾＾
$error =true;
$errorという変数名だと、エラーメッセージが代入されているように思うので、
変数名としては、少し不自然かなと思いました。
一般的には、$check, $is_valid のような変数名がよろしいかなと思います。

次は、バリデーションクラスをインスタンス化して、
もしバリデーションチェックがNGだったら
バリデーションクラスからエラーメッセージを取得して、
ATMクラス内で出力してみたいです。
なので、
今度は、staticではないメソッドにしていただいて、
エラーメッセージをプロパティで管理するようにしていただいて、
バリデーションチェックがNGだったらエラーメッセージをプロパティに格納
エラーメッセージを取得できるメソッドを用意していただき、
そのメソッドを通してエラーメッセージを取得して、
ATMクラスで出力してみてください。

修正箇所
・$errorの変数名を$checkに変更
・残高が0円になるまで引き出しができていませんでした。
    MenuValidationのsetPayTransferValidationないにあるy_transfer ,$money){
        //残高があるか？
        elseif($money < $set_pay_transfer){
            $check = false;
        }
        残高チェックを<= から　<　に修正しました。
・バリデーションクラスのインスタンス化
・エラ〜メッセージの取得

20200717
github を直接修正されたのですね！
せっかくなのでなるべく、ローカル編集→コミット→プッシュの手順でいきましょうか。
sourcetreeを使用していただいているなら、sourcetreeを使用してもOKです。
git のコマンドも最低限、使用できると、さらにベターかと思います。
基本的な流れとしては
//ブランチ確認
git branch
//差分確認
git status
//変更箇所確認
git diff ファイル名
//ステージング
git add ファイル名
//コミット
git commit -m "コミットメッセージ"
//コミットログ
git log -1
//リモートリポジトリにプッシュ
git push
このあたりは、どの現場でも共通かと思うので
今のうちから学習し、
機会があれば、少しずつ現場でも使用するようにしてみてください。
現場だと、失敗できないので、なかなか挑戦できないかと思うのですが
プライベートだと、もし失敗しても作り直せばいいだけなので、問題ないはずです。
gitはプライベートでたくさん触るほどなれるものかと思いますので
ぜひトライしてみてください＾＾

バリデーションクラスのエラーメッセージですが
//エラーメッセージ保持
public $error;
カプセル化にしてみましょうか。

また、エラーメッセージは複数格納できるようにしたいので、
文字列ではなく、配列にしてみましょう。
エラーメッセージの取得方法は、
getErrorMessagesのようなメソッドを通して返すようにしてみましょうか。

それができたら
バリデーションクラスの親クラスを作成してみましょう。
BaseValidation.php というようなクラスを作成し、
すべてのバリデーションメソッドはこのクラスを継承
共通して使用するgetErrorMessagesメソッドは、
この親クラスに移行するイメージです。

修正箇所
・LoginValidation,MenuValidationの$errorをカプセル化
・$errorを配列に変更し、getErrorMessagesよりエラー値を取得するメソッドの作成

・BaseValidation.phpを作成

*/

require_once 'validation/MenuValidation.php';
require_once 'validation/LoginValidation.php';


//ATMの機能

const BALANCE = 1;
const PAYMENT = 2;
const PAYTRANSFER = 3;
const NEXT = 1;
const END = 2;
const COUNTMIN = 2;
const COUNTMAX = 3;
const ERROR = '入力エラー';



const PROCESS = array(
    BALANCE => '残額',
    PAYMENT => '入金',
    PAYTRANSFER => '引き出し',
);
const REPROCESSCHECK = array(
    NEXT => '続ける',
    END => '終了',
);


class User {
    public static $user_list = array(
        1 => array(
            "id" => "1",
            "password" => "1234",
            "name" => "tanaka",
            "balance" => "10000"
        ),
        2 => array(
            "id" => "2",
            "password" => "3456",
            "name" => "suzuki",
            "balance" => "1000000"
        )
    );
    
    public static function getUserList($id){
        //$idが$userの中にあるか？
        for($i=1;$i<=count(self::$user_list);$i++){
            if($id === self::$user_list[$i]['id']){
                //$user_listの値を返す
                return self::$user_list[$i];
            }
        }
        return true;
    }

}

class Atm{
    public $user;
    public static $count = 0;
    public function __construct() {
        //ログイン
        $this->login();
    }
    //ログイン処理
    public function login() {
        //id入力
        //Userクラスのユーザーリストにidがあるかチェック
        //なければエラー、再帰関数
        //Userクラスから指定されたユーザー取得
        $id = $this->idcheckstart();
        //パスワード取得
        //取得したユーザーのパスワードと入力値が一致するかチェック
        //なければエラー、再帰関数
        $this->passWordCheckStart($id);
        //問題なければ、プロパティの$userにセット
        $this->user = User::getUserList($id);
        }

    //メニューと処理
    public function main(){
        //メニュー選択
        //入力した　メニューで、それぞれの機能を呼び出す
        //入力処理
        $process = $this->bankTransactionStart();
        $this->user['balance'] = $this->selectMenu($process);
        //次どうするか？
        $re_process = $this->reProcessStart();
        $this->checkReProcess($re_process);
    }

    //-------------------
    //ログインのメニュー項目
    //-------------------
    public function idCheckStart(){
        $id = $this->idProcess();
        $check = new LoginValidation;
        $id_check = $check->idValidation($id);
        if(!$id_check){
            echo $check->getErrorMessages() . PHP_EOL;
            return $this->idCheckStart();
        }
        return $id;
    } 
    public function idProcess(){
        echo 'idを入力してください。' . PHP_EOL;
        //処理内容取得
        $id = trim(fgets(STDIN));
        return $id;
    }

    public function passWordCheckStart($id){
        $password = $this->passWordProcess($id);
        $check = new LoginValidation;
        $password_check = $check->passwordValidation($id,$password);
        //3回入力間違いした場合終了する
        if($this->count === COUNTMAX){
            exit('3回パスワードを間違えたので終了します。');
        }elseif($this->count <= COUNTMIN){  
            if(!$password_check){
                $this->count++;
                echo $check->getErrorMessages() . '3回まで入力可能' . $this->count . '回目' . PHP_EOL;
                return $this->passWordCheckStart($id);
            }
        }
    } 
    public function passWordProcess($id){
        echo 'idが' . $id . 'のpasswordを入力してください。' . PHP_EOL;
        //処理内容取得
        $password = trim(fgets(STDIN));
        return $password;
    }    

    //----------------
    //最初のメニュー項目
    //----------------
    public function bankTransactionStart(){
        //入力処理
        $process = $this->Process();
        //入力チェックバリデーション
        $check = new MenuValidation;
        $process_check = $check->processValidation($process);
        if(!$process_check){
            echo $check->getErrorMessages() . PHP_EOL;
            return $this->bankTransactionStart();
        }
        return $process;
    }
    public function Process(){
        echo '本日はどうされましたか？' . PHP_EOL . PROCESS[BALANCE] . '•••' . BALANCE . PHP_EOL . PROCESS[PAYMENT] . '•••' . PAYMENT . PHP_EOL . PROCESS[PAYTRANSFER] . '•••' . PAYTRANSFER . PHP_EOL;
        //処理内容取得
        $process = trim(fgets(STDIN));
        return $process;
    }
    public function selectMenu($check){
        switch($check){
            case BALANCE:
                return $this->balance($this->user['balance']);
                break;
            case PAYMENT:
                return $this->payMent($this->user['balance']);
                break;
            case PAYTRANSFER:
                return $this->payTransfer($this->user['balance']);
                break;
        }
    }
    public function balance($money){
        echo PROCESS[BALANCE] . $money . '円です。' . PHP_EOL ;
        return $money;
    }

    public function  payMent($money){
        echo 'いくら入金しますか？';
        //処理内容取得
        $set_pay_ment = trim(fgets(STDIN));
        //半角数字で入力されているかチェック
        $check = new MenuValidation;
        $set_pay_ment_check = $check->spayMentValidation($set_pay_ment);
        if(!$set_pay_ment_check){
            echo $check->getErrorMessages() . PHP_EOL;
            return $this->payMent($money);
        }
        //入金処理
        $this->user['balance'] = $money + $set_pay_ment;
        echo $set_pay_ment . '円入金しました。' . PHP_EOL;
        echo '残高は' . $this->user['balance'] .'円になりました。' . PHP_EOL;
        return $this->user['balance'];
    }


    public function  payTransfer($money){
        echo 'いくら引き出しますか？' . PHP_EOL;
        //処理内容取得
        $set_pay_transfer = trim(fgets(STDIN));
        //半角数字で入力されているかかつ残高より引き出す金額が多く無いかチェック
        $check = new MenuValidation;
        $set_pay_transfer_check = $check->setPayTransferValidation($set_pay_transfer ,$money);
        if(!$set_pay_transfer_check){
            echo $check->getErrorMessages() . PHP_EOL;
            return $this->payTransfer($money);
        }
        $this->user['balance'] = $money - $set_pay_transfer;
        echo $set_pay_transfer . '円引き出しました。' . PHP_EOL;
        echo '残高は' . $this->user['balance'] .'円になりました。' . PHP_EOL;
        return $this->user['balance'];
    }

    //---------
    //継続の判定
    //---------
    public function reProcessStart(){
        $re_process = $this->reProcess();
        $check = new MenuValidation;
        $re_process_check = $check->reProcessValidation($re_process);
        if(!$re_process_check){
            echo $check->getErrorMessages() . PHP_EOL;
            return $this->reProcessStart($this->user['balance']);
        }
        return $re_process;
    }
    public function reProcess(){
        echo REPROCESSCHECK[NEXT] . '•••' . NEXT . PHP_EOL . REPROCESSCHECK[END] .'•••'. END . PHP_EOL;
        //処理内容取得
        $re_process = trim(fgets(STDIN));
        return $re_process;
    }
    public function checkReProcess($re_process){
        switch($re_process){
            case NEXT:
                $this->main($this->user['balance']);
            break;
            case END:
                break;
            }
    }
}

$atm = new Atm;
$atm->main();
