<?php

//BaseValidationはValidationクラスで使用する
//$errorの定義
//getメソッドの定義

class BaseValidation
{
    //エラーメッセージ保持
    public $error = array();

    public function getErrorMessages(){
        return $this->error;
    }

}