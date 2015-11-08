<?php
/**
* サーバー情報
**/
class Server {
  // 現在アクセスされているホストを取得
  public static function getHost() {
    $https = (string)filter_input(INPUT_SERVER, 'HTTPS');
    $host = (string)filter_input(INPUT_SERVER, 'HTTP_HOST');

    return (empty($https) ? "http://" : "https://") . $host;
  }

  // 現在アクセスされているURLを取得
  public static function getURL() {
    $https = (string)filter_input(INPUT_SERVER, 'HTTPS');
    $host = (string)filter_input(INPUT_SERVER, 'HTTP_HOST');
    $uri = (string)filter_input(INPUT_SERVER, 'REQUEST_URI');
    
    return (empty($https) ? "http://" : "https://") . $host . $uri;
  }
}
