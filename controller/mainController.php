<?php
session_start();
require("./db.php");

function salon($db) {
    $showedSalon= '';
    if(isset($_GET['salon'])) {
        $showedSalon = $_GET['salon'];
    }
    else {
        $showedSalon = '0';
    }
    require('./model/salonModel.php');
    if(isset($_POST) && !empty($_POST)) {
        if($_POST['action'] == "0"){
            sendMessage($_POST['salon'], htmlentities($_POST['content']), $db);
        }
        else if($_POST['action'] == "1"){
            addCategory(htmlentities($_POST['name']), $db);
        }
        else if($_POST['action'] == "2"){
            addSalon(htmlentities($_POST['name']), $_POST['channel_category'], $db);
        }
        else if($_POST['action'] == "3"){
            deleteCategory($_POST['categoryId'], $db);
        }
        else if($_POST['action'] == "4"){
            deleteChanel($_POST['channelId'], $db);
        }
    }
    $salons = getChanelList($db);
    $messages = getChanelMessage($showedSalon, $db);
    require('./view/mainVue.phtml');
}

if(!empty($_GET['page']) && $_GET['page'] == 'salon') {
    salon($db);
}
elseif(!empty($_GET['page']) && $_GET['page'] == 'admin') {
    if(!empty($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        require('./view/AdminVue.phtml');
    }
    else {
        header('Location: ?page=login');
    }
}
elseif(!empty($_GET['page']) && $_GET['page'] == 'login') {
    require('./model/loginModel.php');
    $error = '';
    if(isset($_POST) && !empty($_POST)) {
        if($_POST['action'] == "5"){
            $success = login($_POST['email'], $_POST['passwd'], $db);
            if($success == 'true') {
                header('Location: ?page=salon');
            }
            else {
                $error = 'Unable to connect with this creditentials.';
            }
        }
    }
    require('./view/loginVue.phtml');
}
elseif(!empty($_GET['page']) && $_GET['page'] == 'register') {
    require('./model/registerModel.php');
    $error = '';
    if(isset($_POST) && !empty($_POST)) {
        $success = register($_POST['email'], $_POST['passwd'], $db);
        if($_POST['action'] == "6"){
            if($success == 'true') {
                header('Location: ?page=login');
            }
            else {
                $error = 'Unable to register, please try an another mail.';
            }
        }
    }
    require('./view/registerVue.phtml');
}
elseif(!empty($_GET['page']) && $_GET['page'] == 'disconnect') {
    session_destroy();
    header('Location: ?page=salon');
}
else {
    salon($db);
}

