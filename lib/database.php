<?php
/**
* データベース
**/
require_once(dirname(__FILE__) . '/../config/constants.php');

class Database {

  private static $pdo;

  function __construct() {
    // エラー表示
    ini_set('display_errors', 0);
    // 文字コード設定
    mb_internal_encoding(DB_CHARSET);
    // タイムゾーン設定
    date_default_timezone_set(DB_TZ);
    try {
      self::$pdo = $this->connect();
    } catch (PDOException $e) {
      $error = $this->message($e->getMessage);
      var_dump($error);
    }
  }

  /**
  * LIKE検索用エスケープ(LIKE節を用いる際はこの関数をパラメータに適用すること)
  * mysql専用(他のデータベースでは使用不可)
  * @param String $param LIKE節のパラメータ
  * @return String LIKE節用にエスケープした文字列
  **/
  public static function escapeWildcard($param) {
    return '%' . addcslashes($param, '\_%') . '%';
  }

  public function insert($sql = null, $params = null) {
    if ($sql == null || $params == null) {
      return $this->message('SQL文またはパラメータが未設定です。');
    }
    return $this->stdClassToArray($this->rowCount($sql, $params));
  }

  public function select($sql = null, $params = null) {
    if ($sql == null) {
      return $this->message('SQL文が未設定です。');
    }
    return $this->stdClassToArray($this->fetchAll($sql, $params));
  }

  public function count($sql = null, $params = null) {
    if ($sql == null) {
      return $this->message('SQL文が未設定です。');
    }
    return $this->stdClassToArray($this->fetch($sql, $params));
  }

  public function update($sql = null, $params = null) {
    if ($sql == null || $params == null) {
      return $this->message('SQL文またはパラメータが未設定です。');
    }
    return $this->stdClassToArray($this->rowCount($sql, $params));
  }

  public function delete($sql = null, $params = null) {
    if ($sql == null || $params == null) {
      return $this->message('SQL文またはパラメータが未設定です。');
    }
    return $this->stdClassToArray($this->rowCount($sql, $params));
  }

  private function fetch($sql, $params) {
    try {
      $stmt = self::$pdo->prepare($sql);
      $stmt->execute($this->convertArray($params));
      $result = $stmt->fetch();
      return $result;
    } catch (PDOException $e) {
      return $e;
    }
  }

  private function fetchAll($sql, $params) {
    try {
      $stmt = self::$pdo->prepare($sql);
      $stmt->execute($this->convertArray($params));
      return $stmt->fetchAll(PDO::FETCH_CLASS);
    } catch (PDOException $e) {
      return $e;
    }
  }

  private function rowCount($sql, $params) {
    try {
      $stmt = self::$pdo->prepare($sql);
      $stmt->execute($this->convertArray($params));
      return $stmt->rowCount();
    } catch (PDOException $e) {
      return $e;
    }
  }

  private function connect() {
    try {
      $pdo = new PDO(
        // mysql:dbname=testdb;host=localhost;charset=utf8
        DB_PHPTYPE . ':dbname=' . DB_DATABASE . ';host=' . DB_HOST . ';charset=' . DB_CHARSET,
        DB_USERNAME,
        DB_PASSWORD,
        array (
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET `utf8`'
        )
      );
    } catch (PDOException $e) {
      $error = $this->message($e->getMessage());
      throw new Exception($error);
    }
    return $pdo;
  }

  // パラメーター(値, 連想配列, 配列)を配列に変換
  private function convertArray($params) {
    if ($params != null) {
      if (!is_array($params)) {
        return array($params);
      } else if (array_diff_key($params, array_keys(array_keys($params)))) {
        return array_values($params);
      }
    }
    return $params;
  }

  // stdClassを配列に変換
  private function stdClassToArray($obj) {
    if (!is_object($obj) && !is_array($obj)) {
      return $obj;
    }
    $arr = (array)$obj;
    foreach ($arr as $key => $value) {
      unset($arr[$key]);
      $key = str_replace('@', '', $key);
      $arr[$key] = $this->stdClassToArray($value);
    }
    return $arr;
  }

  private function message($text) {
    return DEBUG ? $text : 'システム障害です。';
  }
}
