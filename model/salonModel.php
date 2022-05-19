<?php

function getChanelList($db) {
    $categoryQuery = $db->prepare('SELECT * FROM categories');
    $categoryQuery->execute();
    $categoryResult = $categoryQuery->fetchAll();

    $constructedResult = [];
    foreach($categoryResult as $cat) {
        $categoryQuery = $db->prepare('SELECT name, id FROM salons WHERE category_id = :id');
        $parameters = [
            'id' => $cat['id'],
        ];
        $categoryQuery->execute($parameters);
        $categoryResult = $categoryQuery->fetchAll();
        $arr = (object) [
            'name' => $cat['name'],
            'id' => $cat['id'],
            'salons' => $categoryResult
        ];
        array_push($constructedResult, $arr);
    }

    return($constructedResult);
}

function getChanelMessage($chanel, $db) {
    $messageQuery = $db->prepare('SELECT messages.*, salons.name FROM messages JOIN salons ON salons.id = messages.salon_id WHERE salons.id = :salonId');
    $parameters = [
        'salonId' => $chanel
    ];
    $messageQuery->execute($parameters);
    $messageResult = $messageQuery->fetchAll();

    return $messageResult;
}

function sendMessage($salon, $content, $db) {
    $messageInsert = $db->prepare('INSERT INTO `messages` (`salon_id`, `content`, `post_date`) VALUES (:salonId, :content, :currentDate);');
    $parameters = [
        'salonId' => $salon,
        'content' => $content,
        'currentDate' => time()
    ];
    $messageInsert->execute($parameters);
}

function addSalon($name, $category, $db) {
    $salonInsert = $db->prepare('INSERT INTO `salons` (`id`, `category_id`, `name`)  VALUES (NULL, :catId, :salonName);');
    $parameters = [
        'salonName' => $name,
        'catId' => $category
    ];
    $salonInsert->execute($parameters);
}

function addCategory($name, $db) {
    $catInsert = $db->prepare('INSERT INTO `categories` (`name`) VALUES (:catName);');
    $parameters = [
        'catName' => $name
    ];
    $catInsert->execute($parameters);
}

function deleteCategory($id, $db) {
    $catDelete = $db->prepare('DELETE FROM categories WHERE `categories`.`id` = :id;');
    $parameters = [
        'id' => $id
    ];
    $catDelete->execute($parameters);
}

function deleteChanel($id, $db) {
    $chanDelete = $db->prepare('DELETE FROM salons WHERE `salons`.`id` = :id;');
    $parameters = [
        'id' => $id
    ];
    $chanDelete->execute($parameters);
}