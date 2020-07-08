<?php

const BALANCE = 1;
const PAYMENT = 2;
const PAYTRANSFER = 3;
const NEXT = 1;
const END = 2;

const NOTSET = 1;
const HALFSIZEALL = 2;
const NOTID = 3;
const NOTPASSWORD = 4;


class LoginValidation{

    public static function idValidation($id){
        //falseだとエラー
        $error =false;
        //$idが$userの中にあるか？
        for($i=1;$i<=count(User::$user_list);$i++){
            if($id === User::$user_list[$i]['id']){
                //idのユーザーの配列にある
                return $error =true;
            }
        }        
        return $error;
    }

    public static function passwordValidation($id,$password){
        //falseだとエラー
        $error =false;
        //$idが$userの中にあるか？
        for($i=1;$i<=count(User::$user_list);$i++){
            if($id === User::$user_list[$i]['id']){
                //idの中のpasswordと確認する
                if( $password === User::$user_list[$i]["password"]){
                    return $error =true;
                }
            }
        }
        return $error;
    }



}