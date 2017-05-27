<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Raamatukogu</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div id="container">
    <div id="menu_container">
        <ul id="menu">
            <li><a href="?">Algusesse</a></li>
            <?php if(isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] == 'owner'): ?>
                <li><a href="?page=addBook">Lisa raamat</a></li>
                <li><a href="?page=logout">Logi välja</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div id="content">