#!/usr/bin/env php
<?php

/**
 * Questo script serve per copiare i dati dal vecchio database al nuovo
 */

$old = mysqli_connect("127.0.0.1", "root", "password", "moneylog_old");
$new = mysqli_connect("127.0.0.1", "root", "password", "moneylog");

$userList    = '1';
$accountList = '0';

// COPIO GLI UTENTI ////////////////////////////////////////////////////////////////////////////////////////////////////
if ($results = mysqli_query($old, "SELECT * FROM user WHERE id IN ($userList)")) {
    while (null !== ($row = $results->fetch_assoc())) {
        $id      = $row['id'];
        $name    = $row['name'];
        $surname = $row['surname'];
        $email   = $row['email'];
        $query   = "INSERT INTO user (id, name, surname, email, roles, password) "
                 . "VALUES ('$id', '$name', '$surname', '$email', '[]', '') "
                 . "ON DUPLICATE KEY UPDATE name='$name', surname='$surname', email='$email'";
        echo $query . PHP_EOL;
        mysqli_query($new, $query);
        if (mysqli_errno($new)) {
            echo mysqli_error($new) . PHP_EOL;
        }
    }
}

// COPIO GLI ACCOUNT ///////////////////////////////////////////////////////////////////////////////////////////////////
if ($results = mysqli_query($old, "SELECT * FROM account WHERE userId IN ($userList)")) {
    while (null !== ($row = $results->fetch_assoc())) {
        $id      = $row['id'];
        $userId  = $row['userId'];
        $name    = $row['name'];
        $recap   = $row['recap'];
        $query   = "INSERT INTO account (id, user_id, name, recap) VALUES ($id, $userId, '$name', $recap) "
                 . "ON DUPLICATE KEY UPDATE user_id=$userId, name='$name', recap=$recap";
        echo $query . PHP_EOL;
        $accountList .= ",$id";
        mysqli_query($new, $query);
        if (mysqli_errno($new)) {
            echo mysqli_error($new) . PHP_EOL;
        }
    }
}

// COPIO LE CATEGORIE //////////////////////////////////////////////////////////////////////////////////////////////////
$select = "SELECT id, userId, descrizione, status FROM category WHERE userId IN ($userList)";
if ($results = mysqli_query($old, $select)) {
    while (null !== ($row = $results->fetch_assoc())) {
        $id      = $row['id'];
        $userId  = $row['userId'];
        $name    = $row['descrizione'];
        $enabled = $row['status'];
        $query   = "INSERT INTO category (id, user_id, name, enabled) VALUES ($id, $userId, '$name', $enabled) "
                 . "ON DUPLICATE KEY UPDATE user_id=$userId, name='$name', enabled=$enabled";
        echo $query . PHP_EOL;
        mysqli_query($new, $query);
        if (mysqli_errno($new)) {
            echo mysqli_error($new) . PHP_EOL;
        }
    }
}
if (mysqli_errno($old)) {
    echo mysqli_error($old) . PHP_EOL;
}

// COPIO I MOVIMENTI ///////////////////////////////////////////////////////////////////////////////////////////////////
$select = "SELECT id, accountId, categoryId, date, amount, description FROM movement WHERE accountId IN ($accountList)";
if ($results = mysqli_query($old, $select)) {
    while (null !== ($row = $results->fetch_assoc())) {
        $id         = $row['id'];
        $accountId  = $row['accountId'];
        $categoryId = $row['categoryId'] ?? 'NULL';
        $date       = $row['date'];
        $amount     = $row['amount'];
        $descr      = $row['description'];

        $query = "INSERT INTO movement (id, account_id, date, amount, description, category_id) "
               . "VALUES ($id, $accountId, '$date', $amount, '$descr', $categoryId) "
               . "ON DUPLICATE KEY UPDATE "
               . "account_id=$accountId, date='$date', amount=$amount, description='$descr', category_id=$categoryId";
        echo $query . PHP_EOL;
        mysqli_query($new, $query);
        if (mysqli_errno($new)) {
            echo mysqli_error($new) . PHP_EOL;
        }
    }
}
if (mysqli_errno($old)) {
    echo mysqli_error($old) . PHP_EOL;
}

// COPIO GLI ACCANTONAMENTI ////////////////////////////////////////////////////////////////////////////////////////////
$select = "SELECT id, userId, valuta, importo, descrizione FROM aside WHERE userId IN ($userList)";
if ($results = mysqli_query($old, $select)) {
    while (null !== ($row = $results->fetch_assoc())) {
        $id          = $row['id'];
        $userId      = $row['userId'];
        $date        = $row['valuta'];
        $amount      = $row['importo'];
        $description = $row['descrizione'];

        $query = "INSERT INTO provision (id, user_id, date, amount, description) "
               . "VALUES ($id, $userId, '$date', $amount, '$description') "
               . "ON DUPLICATE KEY UPDATE "
               . "user_id=$userId, date='$date', amount=$amount, description='$description'";
        echo $query . PHP_EOL;
        mysqli_query($new, $query);
        if (mysqli_errno($new)) {
            echo mysqli_error($new) . PHP_EOL;
        }
    }
}
if (mysqli_errno($old)) {
    echo mysqli_error($old) . PHP_EOL;
}
