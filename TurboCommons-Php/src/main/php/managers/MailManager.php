<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use Exception;
use org\turbocommons\src\main\php\model\BaseStrictClass;


/**
 * Class that acts as an interface to email sending operations
 */
class MailManager extends BaseStrictClass {


    /**
     * Stores the number of attached files to the current email
     */
    private $_attachmentsLen = 0;


    /**
     * Structure with the filenames and binary data of the files to attach to the mail
     */
    private $_attachments = array();


    /**
     * Attach a file from binary data to the email
     *
     * @param string $filename The name for the file when sent on the email
     * @param string $binary_data The file binary data to attach
     *
     * @return void
     */
    public function attachFile($filename, $fileData){

        $f = [];
        $f['filename'] = $filename;
        $f['binary'] = chunk_split(base64_encode($fileData));

        $this->_attachments[] = $f;
        $this->_attachmentsLen ++;
    }


    /**
     * Send the email, with the currently specified parameters. Note that if we want to attach files to the mail we must do it before calling the send method.
     *
     * @param string  $senderAddress The addres for the person who sends the email. If you want to set a custom sender name also than the email, use : My custom sender name <mi@email.com>
     * @param array|string $receiverAddress    The address where the email will be sent, or an array containing a list of adresses where the mail will be sent
     * @param string  $subject Title for the message to send
     * @param string  $message Message body
     * @param boolean $htmlMode Enable when sending html content. Disabled by default
     * @param string  $encoding The charset that is defined when sending the email. MailManager::UTF-8 by default. It is VERY important to make sure that the subject and message parameters are passed with the same encoding as the one defined here, otherwise strange characters will appear on the received email.
     * @param boolean $dispositionRequire Request notification when the email is read by the receiver. False by default
     *
     * @return boolean True if the mail was queued to be sent (does not mean it will reach its destination), False if the mail could not be delivered.
     */
    public function sendMail(string $senderAddress,
                             $receiverAddress,
                             string $subject,
                             string $message,
                             bool $htmlMode = false,
                             string $encoding = 'UTF8',
                             bool $dispositionRequire = false){

        // Sanitize the sender and receiver addresses to remove non email characters
        $senderAddress = trim(filter_var($senderAddress, FILTER_SANITIZE_EMAIL));
        $receiverAddress = trim(filter_var($receiverAddress, FILTER_SANITIZE_EMAIL));

        // Some empty values mean we wont deliver the mail
        if($senderAddress == '' || $receiverAddress == '' || $subject.$message == ''){

            return false;
        }

        // Set default charset
        if($encoding == ''){

            $encoding = 'ISO_8859_1';
        }

        // Define the character encoding for the subject and body
        if($encoding == 'UTF8'){

            $encoding = 'charset="UTF8"';

        }else{

            $encoding = 'charset="ISO_8859_1"';
        }

        // Definition for the headers - using \r makes thunderbird fail!!
        $headers = "MIME-Version: 1.0\n";
        $headers .= 'From: '.$senderAddress."\n";
        $headers .= 'Return-Path: '.$senderAddress."\n";
        $headers .= 'Reply-To: '.$senderAddress."\n";

        if($dispositionRequire){

            $headers .= 'Disposition-Notification-To: '.$senderAddress."\n";
        }

        // Check if the mail is in html format
        if($htmlMode){

            $tmp = 'Content-type: text/html; '.$encoding;

        }else{

            $tmp = 'Content-Type: text/plain; '.$encoding;
        }

        // Check if there are attached files
        if($this->_attachmentsLen > 0){

            //create a boundary string. It must be unique so we use the MD5 algorithm to generate a random hash
            $mimeBoundary = '==Multipart_Boundary_x'.md5(time()).'x';

            // Output the multipart mixed headers
            $headers .= 'Content-Type: multipart/mixed; boundary="{'.$mimeBoundary."}\"\n";

            // output the text part for the e-mail
            $emailMessage  = "This is a multi-part message in MIME format.\n\n";
            $emailMessage .= '--{'.$mimeBoundary."}\n";
            $emailMessage .= $tmp."\n";
            $emailMessage .= "Content-Transfer-Encoding: 7bit\n\n";
            $emailMessage .= $message."\n\n";

            // Output all the different attached files
            for($i=0; $i<$this->_attachmentsLen; $i++){

                $emailMessage .= '--{'.$mimeBoundary."}\n";
                $emailMessage .= "Content-Type: application/octet-stream;\n";
                $emailMessage .= ' name="'.$this->_attachments[$i]['filename']."\"\n";
                $emailMessage .= "Content-Disposition: attachment;\n";
                $emailMessage .= ' filename="'.$this->_attachments[$i]['filename']."\"\n";
                $emailMessage .= "Content-Transfer-Encoding: base64\n\n";
                $emailMessage .= $this->_attachments[$i]['binary']."\n\n";
            }

        }else {

            $emailMessage = $message;
            $headers .= $tmp."\n";
        }

        // Send the mail
        try {

            if(!is_array($receiverAddress)){

                $receiverAddress = array($receiverAddress);
            }

            $result = true;

            foreach($receiverAddress as $receiver){

                $result = $result & mail($receiver, $subject, $emailMessage, $headers);
            }

            return $result;

        } catch (Exception $e) {

            return false;
        }
    }
}

?>