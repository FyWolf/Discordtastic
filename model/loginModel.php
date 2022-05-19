<?php
    function login($mail, $pass, $db) {
        $loginQuery = $db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            'email' => $mail
        ];
        $loginQuery->execute($parameters);
        $result = $loginQuery->fetch();
        if(!empty($result)){
            if(password_verify($pass, $result['password'])){
                $_SESSION['userRole'] = $result['role'];
                return 'true';
            }
        }
        else {
            return 'false';
        }
    }