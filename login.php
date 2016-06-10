<?php
require_once './core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true
            ),
            'password' => array(
                'required' => true
            )
        ));
        if ($validation->passed()) {
            $user = new User();
            $remember = (Input::get('remember') === 'on' ? true : false);
            if ($user->login(Input::get('username'), Input::get('password'), $remember)) {
                Redirect::to('index.php');
            }
        } else {
            foreach ($validation->errors() as $key => $value) {
                echo $value . "<br/>";
            }
        }
    }
}
FlashMessage::displayAllFlashMessages();
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
                    <label>Username</label>
                    <span><input type="text" name="username" value="<?php echo escape(Input::get('username')); ?>"/></span>
                </div>
                <div class="form-row">
                    <label>Password</label>
                    <span><input type="text" name="password" /></span>
                </div>
                <div class="form-row">
                    <label>Remember Me</label>
                    <span><input type="checkbox" name="remember" /></span>
                </div>

                <div class="form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <input type="submit" name="submit" value="login">
                </div>
            </form>
        </div>



    </body>
</html>
