<?php

$pdo = new PDO('sqlite:learndb.db');
$test = '2f99cf10-cf38-4e91-aeba-3c896afeea97';
$statement = $pdo->query("SELECT uuid FROM likes WHERE postuuid = '2f99cf10-cf38-4e91-aeba-3c896afeea97' OR useruuid = 'f8dc79e8-d009-4168-a80d-021d6d7ef86f'
;");
var_dump($statement->fetch(PDO::FETCH_ASSOC));