<?php
/**
* 入力フォーマット検証
**/
class Format {
  // 半角英字チェック
  public static function is_alph($str) {
    if(preg_match('/^[a-zA-Z]+$/', $str)) {
      return true;
    }
    return false;
  }

  // 半角数字チェック
  public static function is_number($str) {
    if(preg_match('/^[0-9]+$/', $str)) {
      return true;
    }
    return false;
  }

  // 半角英数字チェック
  public static function is_alphnum($str) {
    if(preg_match('/^[0-9a-zA-Z]+$/', $str)) {
      return true;
    }
    return false;
  }

  // 全角カタカナチェック
  public static function is_katakana($str) {
    if(preg_match('/^[ァ-ヶー]+$/u', $str)) {
      return true;
    }
    return false;
  }

  // 電話番号チェック
  public static function is_telephone($str) {
    if(preg_match('/^[0-9]{3}\-[0-9]{4}\-[0-9]{4}$/', $str)) {
      return true;
    }
    return false;
  }

  // メールアドレスチェック
  public static function is_mail($str) {
    if(preg_match("/^([0-9a-zA-Z])+([0-9a-zA-Z\._-])*@([0-9a-zA-Z_-])+([0-9a-zA-Z\._-]+)+$/", $str)) {
      return true;
    }
    return false;
  }
}