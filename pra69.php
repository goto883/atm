<?php


/*
69 ATMを作成しよう
コマンドラインから実行すること
要件定義
・残額、入金、引き出しの機能を実装
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

    //エラー複数表示
    public function displayError($error){
        for($i=0;$i<count($error);$i++){
            echo $error[$i] . PHP_EOL;
        }
    }

    //-------------------
    //ログインのメニュー項目
    //-------------------
    public function idCheckStart(){
        $id = $this->idProcess();
        $check = new LoginValidation;
        $id_check = $check->idValidation($id);
        if(!$id_check){
            $this->displayError($check->getErrorMessages());
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
                $this->displayError($check->getErrorMessages());
                echo '3回まで入力可能' . $this->count . '回目' . PHP_EOL;
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
            $this->displayError($check->getErrorMessages());
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
            $this->displayError($check->getErrorMessages());
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
            $this->displayError($check->getErrorMessages());
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
            $this->displayError($check->getErrorMessages());
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
