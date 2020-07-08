<?php

const BALANCE = 1;
const PAYMENT = 2;
const PAYTRANSFER = 3;
const NEXT = 1;
const END = 2;

const NOTSET = 1;
const HALFSIZEFIRST = 2;
const HALFSIZEATTER = 3;
const PAYMENTLATTER = 4;
const HALFSIZEALL = 5;
const BALANCENOTENOUGH = 6;


class MenuValidation{

    public static function processValidation($process){
        //falseだとエラー
        $error =true;
        //未入力
        if(empty($process)){
            $error = false;
        }
        //1,2,3で入力してください
        elseif(!($process == BALANCE || $process == PAYMENT || $process == PAYTRANSFER)){
            $error = false;
        }
        return $error;
    }

    public static function reProcessValidation($reProcess){
        //falseだとエラー
        $error =true;
        //未入力
        if(empty($reProcess)){
            $error = false;
        }
        //1,2で入力してください
        elseif(!($reProcess == NEXT || $reProcess == END )){
            $error = false;
        }
        return $error;
    }

    public static function spayMentValidation($set_pay_ment){
        //falseだとエラー
        $error =true;
        //未入力
        if(empty($set_pay_ment)){
            $error = false;
        }
        //半角数字
        elseif(!(preg_match("/^[0-9]+$/",$set_pay_ment))){
            $error = false;
        }
        return $error;
    }

    public static function setPayTransferValidation($set_pay_transfer ,$money){
        //falseだとエラー
        $error =true;
        //未入力
        if(empty($set_pay_transfer)){
            $error = false;
        }
        //半角数字
        elseif(!(preg_match("/^[0-9]+$/",$set_pay_transfer))){
            $error = false;
        }
        //残高があるか？
        elseif($money <= $set_pay_transfer){
            $error = false;
        }
        return $error;
    }



}