<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 29/08/2017
 * Time: 2:02 PM
 */
//echo 'excuted process script! <br>';
$mailSent = false;
$suspect = false;
$pattern = '/Content-type:|Bcc:|Cc:/i';

function isSuspect($value, $pattern, &$suspect){
    if (is_array($value)){
        foreach ($value as $item){
            isSuspect($item,$pattern, $suspect);
        }
    }else{
        if (preg_match($pattern, $value)) {
            $suspect = true;
        }
    }
}
    isSuspect($_POST, $pattern, $suspect);

if (!$suspect):
    foreach ($_POST as $key=>$value) {
        $value = is_array($value)? $value: trim($value);
        if (empty($value) && in_array($key,$required)){
            $missing[] = $key;
            $$key = '';
        }elseif (in_array($key,$expected)){
            $$key = $value;
        }
    }
    if (!$missing && !empty($email))
    {
        $validEmail = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
        if ($validEmail){
            $headers[] = $validEmail;
        }else{
            $errors['email'] = true;
        }
    }
//if no errors create headers and message body
    if(!$errors && !$missing):
        //something
        $headers = implode("\r\n", $headers);
        $message = '';
        foreach ($expected as $item):
            if(isset($$item) && !empty($$item))
            {
                $var =$$item;
            }else{
                $var = 'Not Selected';
            }
            if (is_array($var)){
                $var = implode(', ', $var);
            }
            $field  = str_replace('_',": ",$item);
            $message .=  ucfirst($field)  . ":$var\r\n\r\n";
        endforeach;
        $message = wordwrap($message,70);
        $mailSent =  mail($to, $subject, $message, $headers, $authorized);
        $mailSent =  true;
        if (!$mailSent){
            $errors['mailfail'] = true;
        }
    endif;

endif;