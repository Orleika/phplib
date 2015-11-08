<?php
/**
* リクエスト処理
**/

class Request {
  /**
  * フィルター適用後のパラメータがFALSEかNULLか判定
  * @param $params string フィルター適用したパラメータ
  * @return フィルター変換失敗かパラメータ存在しないときfalse
  **/
  public static function checkParam($param) {
    if ($param === '' || $param === false || is_null($param)) {
      return false;
    }
    return true;
  }

  /**
  * フィルター適用後のパラメータがFALSEかNULLか判定
  * @param $params Array フィルター適用したパラメータ配列
  * @return フィルター変換失敗かパラメータ存在しないときfalse
  **/
  public static function checkParams($params) {
    foreach ($params as $param) {
      if ($param === '' || $param === false || is_null($param)) {
        return false;
      }
    }
    return true;
  }

  /**
  * リクエストしてきたIPがホワイトリストに一致するか判定
  * @param $white_list Array 許可するIPアドレス配列
  * @return bool リスト中1つでも一致したらtrue
  **/
  public static function checkIP($white_list) {
    $remote_addr = (string)filter_input(INPUT_SERVER, 'REMOTE_ADDR');
    
    foreach ($white_list as $ip) {
      if ($ip == $remote_addr) {
        return true;
      }
    }
    return false;
  }
}
