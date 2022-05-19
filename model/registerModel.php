<?php

    function register($mail, $pass, $db) {

        $mailQuery = $db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            'email' => $mail
        ];
        $mailQuery->execute($parameters);
        $result = $mailQuery->fetch();
        if(empty($result)){
            $hashed = password_hash($pass, PASSWORD_DEFAULT);

            $registerQuery = $db->prepare("INSERT INTO `users` (`id`, `email`, `password`, `role`) VALUES (NULL, :email, :passwd, 'user');");
            $parameters = [
                'email' => $mail,
                'passwd' => $hashed
            ];
            $registerQuery->execute($parameters);

            return 'true';
        }
        else {
            return 'false';
        }
    }