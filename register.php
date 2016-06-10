<?php
require_once './core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'name' => 'username',
                'required' => true,
                'min' => Config::get('password/min'),
                'max' => Config::get('password/max'),
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => '6'
            ),
            'confirm_password' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'email' => array(
                'required' => true,
                'email' => true
            )
        ));
        if ($validation->passed()) {
            $salt = Hash::createSalt(32);
            $user = new User();
            if ($user->createUser(array(
                        'username' => Input::get('username'),
                        'password' => Hash::createHash(Input::get('password'), $salt),
                        'email' => Input::get('email'),
                        'salt' => $salt,
                        'created' => date('Y-m-d h:i:s')
                    ))) {
                FlashMessage::pushFlashMessage('Your account has been created successfully', 'success');
                Redirect::to('index.php');
            } else {
                
            }
        } else {
            
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
                    <label>Username</label>
                    <span><input type="text" name="username" value="<?php echo escape(Input::get('username')); ?>"/></span>
                </div>
                <div class="form-row">
                    <label>Password</label>
                    <span><input type="text" name="password" /></span>
                </div>
                <div class="form-row">
                    <label>Renter Password</label>
                    <span><input type="text" name="confirm_password" /></span>
                </div>
                <div class="form-row">
                    <label>Email</label>
                    <span><input type="text" name="email" value="<?php echo escape(Input::get('email')); ?>"/></span>
                </div>
                <div class="form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <input type="submit" name="submit" >
                </div>
            </form>
        </div>



    </body>
</html>
