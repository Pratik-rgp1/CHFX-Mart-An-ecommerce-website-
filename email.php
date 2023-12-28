<?php

    $to      = $sendemail;    
    $subject = $subj;
    $message = $body;

    $headers = 'From: chfxmart13@gmail.com' . "\r\n" .'Reply-To: chfxmart13@gmail.com';
    
    $mail_sent = mail($to, $subject, $message, $headers);
 
    if(!$mail_sent){
        echo "mail send failed";
    }  
    ?>