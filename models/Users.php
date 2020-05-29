<?php

namespace models;

class Users extends \core\Model
{
    public function __construct()
    {
    }
    public function Authenticate($login, $password)
    {
        global $core;
        $query = new \core\DBQuery('users');
        $res = $core->getDB()->executeQuery($query->select('COUNT(*) as count')
            ->where(['login' => $login, 'password' => $password])->one());
        return $res['count'] != 0;
    }
}
