<?php
/**
* Eメール
**/
require_once(dirname(__FILE__) . '/../config/constants.php');

class Email {

  private $to;
  private $subject;
  private $message;
  private $from;
  private $return;
  private $cc = null;
  private $bcc = null;

  /**
  * Eメールコンストラクタ
  * 送信するときは以下のコードを実行すること(インスタンス化のみでは送信されない)
  * $email = new Email($to, $subject, $message);
  * $email->send();
  * @param string $to 送信先アドレス
  * @param string $subject メールタイトル
  * @param string $message メール本文
  * @return array $email メールオブジェクト
  **/
  function __construct($to = null, $subject = null, $message = null) {
    if ($to == null || $subject == null || $message == null) {
      return null;
      exit();
    }
    mb_language('japanese');
    mb_internal_encoding('UTF-8');
    $this->from = 'From: ' . EMAIL_FROM;
    $this->to = $to;
    $this->subject = $subject;
    $this->message = $message;
    $this->return = '-f ' . EMAIL_RETURN; 
    return $this;
  }

  public function setCC($cc) {
    $this->cc = array();
    array_push($this->cc, $cc);
  }

  public function setBCC($bcc) {
    $this->bcc = array();
    array_push($this->bcc, $bcc);
  }

  public function send() {
    $header = $this->from . PHP_EOL;
    if ($this->cc != null) {
      $header .= 'CC: ' . implode(',', $this->cc) . PHP_EOL;
    }
    if ($this->bcc != null) {
      $header .= 'BCC: ' . implode(',', $this->bcc) . PHP_EOL;
    }
    return mb_send_mail($this->to, $this->subject, $this->message, $header, $this->return);
  }
}
