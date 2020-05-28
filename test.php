<?php

include('config/config.php');
include('core/DB.php');
include('core/DBQuery.php');
// SELECT testing
$querySelect = new \core\DBQuery('users');
$querySelect->select()->where(['login' => 'admin']);


//INSERT testing
$queryInsert = new \core\DBQuery('users');
$queryInsert->insert([
    'login' => 'newuser',
    'password' => '123456',
    'firstname' => 'Anton',
    'lastname' => 'Andreev'
]);

//DELETE testing
$queryDelete = new \core\DBQuery('users');
$queryDelete->delete()->where(['id' => 2]);

// UPDATE testing
$queryUpdate = new \core\DBQuery('users');
$queryUpdate->update(['password' => 'qawsed'])->where(['id' => 3]);
var_dump($queryUpdate->getSQL());

$db = new \core\DB(
    //TODO
    // $CMSConfig['Datebase']['Server'],
    // $CMSConfig['Datebase']['User'],
    // $CMSConfig['Datebase']['Password'],
    // $CMSConfig['Database']['DatebaseName']
    'localhost',
    'root',
    'root',
    'project_bd'
);
$db->executeQuery($queryInsert);
$db->executeQuery($queryDelete);
$db->executeQuery($queryUpdate);

$res = $db->executeQuery($querySelect);
var_dump($res);
