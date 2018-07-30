<?php
/**
* Description of WebBbsMainCmtDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
class EmailDAO extends BaseDAO{

    function __construct() {
        parent::__construct();
        $this->cfg = Config::getConfig();

    }

    function mailer($to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
    {
        include_once ('./Plugin/PHPMailer/PHPMailerAutoload.php');

        if ($type != 1)
            $content = nl2br($content);

        $mail = new PHPMailer(); // defaults to using php "mail()"

        $mail->isSMTP(); // telling the class to use SMTP
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;   // debug on 3 off 0
        if($this->cfg['email']['secure']) $mail->SMTPSecure = $this->cfg['email']['secure']; // sets the prefix to the servier
        $mail->Host = $this->cfg['email']['host']; // sets GMAIL as the SMTP server
        $mail->Port = $this->cfg['email']['port']; // set the SMTP port for the GMAIL server
        $mail->Username = $this->cfg['email']['username']; // GMAIL username
        $mail->Password = $this->cfg['email']['password']; // GMAIL password
        if($mail->Password) $mail->SMTPAuth = true; // enable SMTP authentication
//        $mail->From = $this->cfg['manage']['email'];
//        $mail->FromName = $this->cfg['manage']['name'];
        $mail->From = $this->cfg['manage']['email'];
        $mail->FromName = $this->cfg['manage']['name'];

        $mail->Subject = $subject;
        $mail->AltBody = ""; // optional, comment out and test
        $mail->MsgHTML($content);
        $mail->AddAddress($to);
        if ($cc)
            $mail->AddCC($cc);
        if ($bcc)
            $mail->AddBCC($bcc);
        //print_r2($file); exit;
        if ($file != "") {
            foreach ($file as $f) {
                $mail->AddAttachment($f['path'], $f['name']);
            }
        }
        return ($mail->send())?ResError::no:-1;
    }

    function __destruct(){

    }
}
