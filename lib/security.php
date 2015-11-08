<?php
/**
* セキュリティ
* ※CSRNGではない関数が使用されている
**/
require_once(dirname(__FILE__) . '/../config/constants.php');

class Security {
  // エスケープ処理
  public static function escape($s){
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }

  // CSRF対策トークン生成(半角英数字40文字)
  public static function setToken($scope) {
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION[$scope]['token'] = $token;
  }

  // CSRF対策トークンチェック
  public static function checkToken($scope) {
    $token = (string)filter_input(INPUT_POST, 'token');

    if($_SESSION[$scope]['token'] === $token) {
      return true;
    }
    return false;
  }

  // ログイン状態に移行(ログアウトはSessionライブラリのdestroyを実行すること)
  public static function setLogin() {
    return self::setToken('login');
  }

  // ログインID発行
  public static function generateLoginID($length) {
    $id = '';
    for ($i = 0; $i < $length; $i++)
      $id .= mt_rand(0, 9);
    return $id;
  }

  // ID発行($max_lengthが25ならユニーク)
  public static function generateID($max_length) {
    return substr(uniqid(mt_rand()), 0, $max_length);
  }

  // パスワード発行
  public static function generatePassword($size) {
    $password_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $password_chars_count = strlen($password_chars);
    $data = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
    $pin = '';
    for ($n = 0; $n < $size; $n ++) {
      $pin .= substr($password_chars, ord(substr($data, $n, 1)) % $password_chars_count, 1);
    }
    return $pin;
  }

  // 暗号化(双方向)
  public static function encrypt($msg) {
    //初期化ベクトルを生成
    $ivSize = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_CFB);
    $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_URANDOM);
    $dummyIV = str_repeat("x", $ivSize);

    //メッセージの暗号化
    $cryptMsg = mcrypt_encrypt(MCRYPT_DES, CRYPT_KEY, base64_encode($msg), MCRYPT_MODE_CFB, $iv);
    // 初期化ベクトルの暗号化
    $cryptIV = mcrypt_encrypt(MCRYPT_DES, CRYPT_KEY, base64_encode($iv), MCRYPT_MODE_CFB, $dummyIV);

    return array($cryptMsg, $cryptIV);
  }

  // 複合化(双方向)
  public static function decrypt($cryptMsg, $cryptIV) {
    //ダミーの初期化ベクトルを生成
    $ivSize  = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_CFB);
    $dummyIV = str_repeat("x", $ivSize);

    // 初期化ベクトルの復号
    $iv = _decryptSupport($cryptIV, $dummyIV);

    //メッセージの復号
    $msg = _decryptSupport($cryptMsg, $iv);

    // メッセージの復号
    return $msg;
  }

  function _decryptSupport($cryptMsg, $iv) {
    // 復号, NULLバイト除去, base64デコード
    return base64_decode(rtrim(mcrypt_decrypt(MCRYPT_DES, CRYPT_KEY, $cryptMsg, MCRYPT_MODE_CFB, $iv), "\0"));
  }
}
