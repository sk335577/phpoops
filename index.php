<?php
require_once './core/init.php';
$user = new User();
FlashMessage::displayAllFlashMessages();
if ($user->isUserLoggedIn()) {
    ?>
    <div>
        <ul>
            <li>Hello <?php echo $user->data()->username; ?></li>
            <li>
                <ul>
                    <li><a href="logout.php" >Logout</a></li>
                    <li><a href="update-profile.php" >Update profile</a></li>
                    <li><a href="change-password.php" >Change password</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <?php
} else {
    ?>
    <div>
        <ul>
            <li>Please log in</li>
            <li>
                <ul>
                    <li><a href="login.php" >Login</a></li>
                    <li><a href="register.php" >Register</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <?php
}


