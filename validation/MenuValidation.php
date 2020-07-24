<?php

// require_once 'validation/BaseValidation.php';
require_once 'BaseValidation.php';


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
const NOPROCESS = 7;
const NOREPROCESS = 8;

const ERRORMENU = array(
    NOTSET => '未入力です',
    NOPROCESS => '1,2,3から選択してください',
    NOREPROCESS => '1,2から選択してください',
    HALFSIZEALL => '半角数字で入力してください',
    BALANCENOTENOUGH => '残高がたりません',
    );


class MenuValidation extends BaseValidation
{
    public function processValidation($process){
        //falseだとエラー
        $check =true;
        $this->error = array();

        //未入力
        if(empty($process)){
            $this->error = ERRORMENU[NOTSET];
            $check =false;
        }
        //1,2,3で入力してください
        elseif(!($process == BALANCE || $process == PAYMENT || $process == PAYTRANSFER)){
            $this->error = ERRORMENU[NOPROCESS];
            $check =false;
        }
        return $check;
    }

    public function reProcessValidation($reProcess){
        //falseだとエラー
        $check =true;
        $this->error = array();

        //未入力
        if(empty($reProcess)){
            $check = false;
            $this->error = ERRORMENU[NOTSET];
        }
        //1,2で入力してください
        elseif(!($reProcess == NEXT || $reProcess == END )){
            $check = false;
            $this->error = ERRORMENU[NOREPROCESS];
        }
        return $check;
    }

    public function spayMentValidation($set_pay_ment){
        //falseだとエラー
        $check =true;
        $this->error = array();

        //未入力
        if(empty($set_pay_ment)){
            $check = false;
            $this->error = ERRORMENU[NOTSET];
        }
        //半角数字
        elseif(!(preg_match("/^[0-9]+$/",$set_pay_ment))){
            $check = false;
            $this->error = ERRORMENU[HALFSIZEALL];
        }
        return $check;
    }

    public function setPayTransferValidation($set_pay_transfer ,$money){
        //falseだとエラー
        $check =true;
        $this->error = array();

        //未入力
        if(empty($set_pay_transfer)){
            $check = false;
            $this->error = ERRORMENU[NOTSET];
        }
        //半角数字
        elseif(!(preg_match("/^[0-9]+$/",$set_pay_transfer))){
            $check = false;
            $this->error = ERRORMENU[HALFSIZEALL];
        }
        //残高があるか？
        elseif($money < $set_pay_transfer){
            $check = false;
            $this->error = ERRORMENU[BALANCENOTENOUGH];
        }
        return $check;
    }

    public function getErrorMessages(){
        return $this->error;
    }
    
}