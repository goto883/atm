<?php

//ここの$errorメッセージの項目修正中
//true,falseのチェック項目の確認


const BALANCE = 1;
const PAYMENT = 2;
const PAYTRANSFER = 3;
const NEXT = 1;
const END = 2;

const NOTSET = 1;
const HALFSIZEALL = 2;
const NOTID = 3;
const NOTPASSWORD = 4;

const ERROR = array(
    NOTSET => '未入力です',
    HALFSIZEALL => '半角数字で入力してください',
    NOTID => 'IDがありません',
    NOTPASSWORD => 'PASSWORDがありません',
    );


class LoginValidation{
    //エラーメッセージ保持
    public $error;

    public function idValidation($id){
        //falseだとエラー
        $check =false;
        $this->error = '';

        //未入力
        if(empty($id)){
            $this->error = ERROR[NOTSET];
        }
        //半角数字
        elseif(!(preg_match("/^[0-9]+$/",$id))){
            $this->error = ERROR[HALFSIZEALL];
        }
        //$idが$userの中にあるか？
        for($i=1;$i<=count(User::$user_list);$i++){
            if($id === User::$user_list[$i]['id']){
                //idのユーザーの配列にある
                return $check =true;
            }
        }
        if(empty($this->error)){
            $this->error = ERROR[NOTID];
        }
        return $check;
    }

    public function passwordValidation($id,$password){
        //falseだとエラー
        $check =false;
        $this->error = '';

        //未入力
        if(empty($password)){
            $this->error = ERROR[NOTSET];
        }
        //半角数字
        elseif(!(preg_match("/^[0-9]+$/",$password))){
            $this->error = ERROR[HALFSIZEALL];
        }
        //$idの$passwordが$userの中にあるか？
        for($i=1;$i<=count(User::$user_list);$i++){
            if($id === User::$user_list[$i]['id']){
                //idの中のpasswordと確認する
                if( $password === User::$user_list[$i]["password"]){
                    return $check =true;
                }
            }
        }
        if(empty($this->error)){
            $this->error = ERROR[NOTPASSWORD];
        }
        return $check;
    }



}