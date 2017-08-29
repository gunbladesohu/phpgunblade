<?php
$errors = [];
$missing = [];
$required = [];
$expected =[];
if (isset($_POST['send'])) {
    $required = ['name','comments'];
    $expected = ['name','email','comments'];
    $to = 'David Powers <david@example.com>';
    $subject = 'Feedback from online form';
    $headers = [];
    $headers[] = 'From: webmaster@example.com';
    $headers[] = 'Cc: another@example.com';
    $headers[] = 'Content-type: text/plain; charset=utf-8';
    $authorized = null;
    include_once './includes/process_email.php';
    if($mailSent ){
        header('Location: thanks.php');
        exit;
    }
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Conditional error messages</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Contact Us</h1>
<?php if ($_POST && ($suspect ||isset($errors['mailfail']))) : ?>
    <p class="warning">Sorry your email cannot be sent</p>
<?php elseif ($errors || $missing) : ?>
    <p class="warning">Please fix the item(s) indicated</p>
<?php endif; ?>

<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
  <p>
    <label for="name">Name:
    <?php if ($missing && in_array('name', $missing)) : ?>
        <span class="warning">Please enter your name</span>
    <?php endif; ?>
    </label>
    <input type="text" name="name" id="name"<?php
    if ($missing||$errors){
        echo 'value = "' . htmlentities($name) . '"';
    }
    ?>>
  </p>
  <p>
    <label for="email">Email:
        <?php if ($missing && in_array('email', $missing)) : ?>
            <span class="warning">Please enter your email address</span>
            <?php elseif(isset($errors['email'])): ?>
            <span class="warning">Invaild email address</span>
        <?php endif; ?>
    </label>
    <input type="email" name="email" id="email" <?php
    if ($missing||$errors){
        echo 'value = "' . htmlentities($email) . '"';
    }
    ?>>
  </p>
  <p>
    <label for="comments">Comments:
        <?php if ($missing && in_array('comments', $missing)) : ?>
            <span class="warning">You forgot to add any comments</span>
        <?php endif; ?>
    </label>
    <textarea name="comments" id="comments"><?php
        if ($missing||$errors){
            echo htmlentities($comments) ;
        }
        ?></textarea>
  </p>
  <p>
    <input type="submit" name="send" id="send" value="Send Comments">
  </p>
</form>
<pre>
    <?php //echo '$errors is ';print_r($errors); echo '<br>';?>
    <?php //echo '$missing is ';print_r($missing); echo '<br>';?>
    <?php //echo '$required is ';print_r($required); echo '<br>';?>
    <?php //echo '$expected is ';print_r($expected); echo '<br>';?>

    <?php
//        if ($_POST && $mailSent):
//            echo "Message: \n\n";
//            echo htmlentities($message);
//            echo "header: \n\n";
//            echo htmlentities($headers);
//        endif;
    ?>
</pre>
</body>
</html>