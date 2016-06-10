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
            'old_password' => array(
                'required' => true,
                'min' => Config::get('password/min')
            ),
            'new_password' => array(
                'required' => true,
                'min' => Config::get('password/min')
            ),
            'confirm_password' => array(
                'required' => true,
                'matches' => 'new_password'
            ),
        ));
        if ($validation->passed()) {
            if (Hash::createHash(Input::get('old_password'), $user->data()->salt) !== $user->data()->password) {
                echo "wrong";
            } else {
                $salt = Hash::createSalt(32);
                if ($user->update(array(
                            'password' => Hash::createHash(Input::get('new_password'), $salt),
                            'salt' => $salt
                        ))) {
                    FlashMessage::pushFlashMessage('Your password has been updated successfully', 'success');
                    Redirect::to('index.php');
                }
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
                    <label>Old Password</label>
                    <span><input type="text" name="old_password" /></span>
                </div>
                <div class="form-row">
                    <label>New Password</label>
                    <span><input type="text" name="new_password" /></span>
                </div>
                <div class="form-row">
                    <label>New Password again</label>
                    <span><input type="text" name="confirm_password" /></span>
                </div>

                <div class="form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <input type="submit" name="submit" >
                </div>
            </form>
        </div>



    </body>
</html>



