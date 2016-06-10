<?php
require_once './core/init.php';
$user = new User();
if (!$user->isUserLoggedIn()) {
    Redirect::to('login.php');
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'firstname' => array(
                'name' => 'first name',
                'required' => true
            )
        ));
        if ($validation->passed()) {

            if ($user->update(array(
                        'firstname' => Input::get('firstname')
                    ))) {
                FlashMessage::pushFlashMessage('Your profile has been updated successfully', 'success');
                Redirect::to('index.php');
            } else {
                
            }
        } else {
            foreach ($validation->errors() as $key => $value) {
                echo $value . "<br/>";
            }
        }
    }
}
?><!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>

            .form-container {
                background-color: #f5f5f5;
                margin: 0 auto;
                padding: 20px;
                width: 80%;
            }
            .form-row {
                display: block;
                margin: 19px 0;
            }
            .form-container label {
                display: inline-block;
                width: 20%;
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-row">
                    <label>Firstname</label>
                    <span><input type="text" name="firstname" value="<?php echo escape($user->data()->firstname); ?>"/></span>
                </div>

                <div class="form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <input type="submit" name="submit" >
                </div>
            </form>
        </div>



    </body>
</html>

