<?php

mb_language('Japanese');
mb_internal_encoding('UTF-8');

/**
 * メール送信処理
 */
class SendMail
{
    private $transArr = ['&lt;'=>'<', '&gt;'=>'>', '&quot;'=>'"', '&#039;'=>"'", '&amp;'=>'&'];
    private $fromEncoding = "UTF-8";

    private $from;
    private $to;
    private $cc;
    private $bcc;
    private $subject;
    private $body;
    private $header;

    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * 初期化
     *
     * @access public
     */
    public function init()
    {
        $this->to      = [];
        $this->cc      = [];
        $this->bcc     = [];
        $this->subject = null;
        $this->body    = null;
        $this->header  = [];
    }

    /**
     * メールの送信
     *
     * @access public
     * @param bool $initFlg 初期化フラグ（省略時はtrue）
     * @return boolean メール送信エラーの場合false
     */
    public function send($initFlg = true)
    {

        $additional_header = "From: {$this->from}\n";
        $additional_header .= 'MIME-version: 1.0' . "\n";
//        $additional_header .= 'Content-Type: text/plain; charset="iso-2022-jp"' . "\n";

        $to      = implode(',', $this->to);
        $subject = $this->subject;
        if (count($this->cc) > 0)  {$additional_header .= "Cc: ". implode(',', $this->cc) . "\n";}
        if (count($this->bcc) > 0) {$additional_header .= "Bcc: ". implode(',', $this->bcc) . "\n";}

        for ($i=0; $i<count($this->header); $i++ ) {
            $additional_header .= "{$this->header[$i]["name"]}: {$this->header[$i]["value"]}\n";
        }

        // メール送信
        $ret = (bool)mb_send_mail($to, $subject, $this->body, $additional_header);

        // メール内容初期化
        if ($initFlg) {$this->init();}

        return $ret;
    }

    /**
     * 送信元アドレスのセット
     *
     * @access public
     * @param $from 送信元アドレス
     * @param $from_name 送信元アドレス名
     */
    public function setFrom($from, $from_name = null)
    {
        $from_name = mb_convert_kana($from_name, "KVa", $this->fromEncoding);
        $this->from = $this->getEncodeMimeheader($from, $from_name);
    }

    /**
     * 送信先アドレスの追加
     *
     * @access public
     * @param $to 送信先アドレス
     * @param $to_name 送信先アドレス名
     */
    public function addTo($to, $to_name = null)
    {
        $this->to[] = $this->getEncodeMimeheader($to, $to_name);
    }

    /**
     * ccアドレスの追加
     *
     * @access public
     * @param $cc ccアドレス
     * @param $cc_name ccアドレス名
     */
    public function addCc($cc, $cc_name = null)
    {
        $this->cc[] = $this->getEncodeMimeheader($cc, $cc_name);
    }

    /**
     * bccアドレスの追加
     *
     * @access public
     * @param $bcc bccアドレス
     * @param $bcc_name bccアドレス名
     */
    public function addBcc($bcc, $bcc_name = null)
    {
        $this->bcc[] = $this->getEncodeMimeheader($bcc, $bcc_name);
    }

    /**
     * 表題のセット
     *
     * @access public
     * @param $subject 表題アドレス
     */
    public function setSubject($subject)
    {
        $send_subject = $this->getReverseStr(str_replace("\x00", "", $subject));
        $send_subject = mb_convert_kana($send_subject, "K", $this->fromEncoding);
        if (isset($send_subject)) {$this->subject = $send_subject;}
    }

    /**
     * 内容のセット
     *
     * @access public
     * @param $from 内容
     */
    public function setBody($body)
    {
        $send_body = $this->getReverseStr(str_replace("\x00", "", $body));
        $send_body = mb_convert_kana($send_body, "K", $this->fromEncoding);
        if (isset($send_body)) {$this->body = $send_body;}
    }

    /**
     * ヘッダー追加
     *
     * @access public
     * @param $name フィールド名
     * @param $value フィールドの値
     */
    public function addHeader($name, $value)
    {
        $header_cnt = count($this->header);
        $this->header[$header_cnt]["name"]  = $name;
        $this->header[$header_cnt]["value"] = $value;
    }

    /**
     * メールアドレスをエンコードした状態で返却
     *
     * @access private
     * @param  $address アドレス
     * @param  $name    アドレス名
     * @return string   エンコードしたアドレス
     */
    private function getEncodeMimeheader($address, $name)
    {
        $send_address = str_replace("\x00", "", $address);
        $send_name = $this->getReverseStr(str_replace("\x00", "", $name));
        if (isset($name)) {
            $rtn = mb_encode_mimeheader($send_name)." <{$send_address}>";
        } else {
            $rtn = $send_address;
        }
        return $rtn;
    }

    /**
     * メール送信用文字列変換
     *
     * @access private
     * @param  string   $str    変換したい文字列
     * @return string   変換後文字列
     */
    private function getReverseStr($str)
    {
        $str = mb_convert_kana($str, 'KV');         //半角カタカナ->全角変換
        $str = $this->reverseSpecialChars($str);   //特殊文字を戻す
        return $str;
    }

    /**
     * 特殊文字の一部をプレーンテキストに戻す
     *
     * @access private
     */
    private function reverseSpecialChars($val)
    {
        $val = strtr($val, $this->transArr);
        return $val;
    }

}
