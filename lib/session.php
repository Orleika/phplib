<?php
/**
* セッション管理
**/
class Session {
  // ハッシュデータをセッション登録
  public static function setHash($hash, $scope) {
    foreach($hash as $key => $data) {
      $_SESSION[$scope][$key] = $data;
    }
  }

  // スコープのハッシュ削除
  public static function destroyScope($scope) {
    $_SESSION[$scope] = array();
  }

  // 配列ハッシュデータをセッション登録
  public static function setArrayHash($array, $scope) {
    $row = count($array);
    for ($i = 0; $i < $row; $i++) {
      foreach ($array[$i] as $key => $value) {
        $_SESSION[$scope][$i][$key] = $value;
      }
    }
  }

  // セッション情報の破棄
  public static function destroy() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
  }
}