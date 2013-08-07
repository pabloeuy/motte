<?php
/**
 * Clase para el manejo de mails (Wrapper para phpMailer)
 *
 * @filesource
 * @package motte
 * @subpackage misc
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */

    class mteMail {

        private $_mail;
        private $_cc;
        private $_bcc;

        /**
         * Constructor
         *
         * @access public
         * @return mteMail
         */
        public function __construct() {
            $this->_mail = new phpMailer();
            $this->_cc   = array();
            $this->_bcc  = array();

            $this->_mail->mailer     = MTE_MAIL_SEND_METHOD;
            $this->_mail->FromName   = MTE_MAIL_FROM_NAME;
            $this->_mail->From       = MTE_MAIL_FROM_ADDRESS;
            $this->_mail->Username   = MTE_MAIL_SMTP_USER;
            $this->_mail->Password   = MTE_MAIL_SMTP_PASSWORD;
            $this->_mail->Host       = MTE_MAIL_SMTP_HOST;
            $this->_mail->Port       = MTE_MAIL_SMTP_PORT;
            $this->_mail->SMTPSecure = MTE_MAIL_SMTP_SECURE;
            $this->_mail->AuthType   = MTE_MAIL_AUTHTYPE;
            $this->_mail->Auth       = MTE_MAIL_AUTH;
            $this->_mail->SMTPDebug  = MTE_MAIL_SMTP_DEBUG;
            $this->_mail->AddReplyTo(MTE_MAIL_REPLYTO_ADDRESS, MTE_MAIL_REPLYTO_NAME);

            switch($this->_mail->mailer) {
                case 'mail':
                {
                    $this->_mail->IsMail();
                    break;
                }
                case 'sendmail':
                {
                    $this->_mail->IsSendmail();
                    break;
                }
                case 'smtp':
                {
                    $this->_mail->IsSMTP();
                    break;
                }
                case 'qmail':
                {
                    $this->_mail->IsQmail();
                    break;
                }
                default:
                {
                    $this->_mail->IsMail();
                    break;
                }
            }
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        /**
        * Add TO: destination mail address
        *
        * @param string $address
        * @param string $name
        * @access public
        */
        public function addTo($address = '', $name = ''){
            $this->_mail->AddAddress($address,$name);
        }

        public function getError() {
            return $this->_mail->ErrorInfo;
        }


        /**
        * Add CC: destination mail address
        *
        * @param string $address
        * @param string $name
        * @access public
        */
        public function addCC($address, $name = ''){
            $this->_mail->AddCC($address,$name);

            $aux = explode(',', $address);
            foreach ($aux as $addr) {
                $this->_cc[] = $name.' <'.trim($addr).'>';
            }
        }

        /**
        * Add BCC: destination mail address
        *
        * @param string $address
        * @param string $name
        * @access public
        */
        public function addBCC($address, $name = ''){
            $this->_mail->AddBCC($address,$name);

            $aux = explode(',', $address);
            foreach ($aux as $addr) {
                $this->_bcc[] = $name.' <'.trim($addr).'>';
            }
        }

        /**
        * Sets mail subject
        *
        * @param string $subject
        * @access public
        */
        public function setSubject($subject=''){
            if (!defined('MAIL_SUBJECT_PREFIX')){
                define('MAIL_SUBJECT_PREFIX', MTE_SYSTEM_NAME.' - ');
            }
            $this->_mail->Subject = MAIL_SUBJECT_PREFIX.$subject;
        }

        /**
        * Sets FROM: mail address
        *
        * @param string $address
        * @param string $name
        * @access public
        */
        public function setFrom($address, $name){
            $this->_mail->From     = $address;
            $this->_mail->FromName = $name;
        }

        /**
        * Sets mail HTML Body
        *
        * @param string $body
        * @access public
        */
        public function setHTMLBody($body, $altBody = ''){
            $this->_mail->Body    = $body;
            $this->_mail->AltBody = $altBody;
            $this->_mail->IsHtml(true);
        }

        /**
        * Sets mail Plain text Body
        *
        * @param string $body
        * @access public
        */
        public function setBody($body){
            $this->_mail->Body    = $body;
            $this->_mail->AltBody = '';
            $this->_mail->IsHtml(false);
        }

        /**
        * Sets REPLY-TO value
        *
        * @param string $address
        * @param string $name
        * @access public
        */
        public function addReplyTo($address, $name = '') {
            $this->_mail->AddReplyTo($address, $name);
        }

        /**
        * Adds a file to mail (attachment)
        *
        * @param string $path
        * @param string $name
        * @param string $encoding
        * @param string $type
        * @return boolean
        * @access public
        */
        public function addAttachment ($path, $name, $encoding = "base64", $type = "application/octet-stream") {
            return $this->_mail->AddAttachment($path, $name, $encoding, $type);
        }

        /**
        * Adds a String file to mail (string attachment)
        *
        * @param string $string
        * @param string $filename
        * @param string $encoding
        * @param string $type
        * @return boolean
        * @access public
        */
        public function addStringAttachment ($string, $filename, $encoding = "base64", $type = "application/octet-stream") {
            return $this->_mail->AddStringAttachment($string, $filename, $encoding, $type);
        }

        /**
        * Adds a an image to mail body (Image Embbeding)
        *
        * @param string $path
        * @param string $cid
        * @param string $name
        * @param string $encoding
        * @param string $type
        * @return boolean
        * @access public
        */
        public function addEmbeddedImage($path, $cid, $name = '', $encoding = "base64", $type = "application/octet-stream") {
            return $this->_mail->AddEmbeddedImage($path, $cid, $name, $encoding, $type);
        }

        /**
        * Adds a custom email header
        *
        * @param string $custom_header
        * @access public
        */
        public function addCustomerHeader($custom_header) {
            $this->_mail->AddCustomHeader($custom_header);
        }

        /**
        * sets mails language
        *
        * @param string $lang_type
        * @param string $lang_path
        * @return boolean
        * @access public
        */
        public function setLang($lang_type, $lang_path = "language/") {
            return $this->_mail->SetLanguage($lang_type, $lang_path);
        }

        /**
        * Sends mail
        *
        * @return boolean
        * @access public
        */
        public function send() {
            // headers
            $headerCC = '';
            if (count($this->_cc) > 0){
                $headerCC = 'Cc: '.implode(', ',$this->_cc)."\r\n";
            }
            $headerBCC = '';
            if (count($this->_bcc) > 0){
                $headerBCC = 'Bcc: '.implode(', ',$this->_bcc)."\r\n";
            }

            switch($this->_mail->mailer) {
                case 'mail':
                {
                    $this->_mail->IsMail();
                    $this->addCustomerHeader($headerCC.$headerBCC);
                    break;
                }
                case 'sendmail':
                {
                    $this->_mail->IsSendmail();
                    $this->addCustomerHeader($headerBCC);
                    break;
                }
                case 'smtp':
                {
                    $this->_mail->IsSMTP();
                    break;
                }
                case 'qmail':
                {
                    $this->_mail->IsQmail();
                    break;
                }
                default:
                {
                    $this->_mail->IsMail();
                    $this->addCustomerHeader($headerCC.$headerBCC);
                    break;
                }
            }
            return $this->_mail->Send();
        }
    }
?>