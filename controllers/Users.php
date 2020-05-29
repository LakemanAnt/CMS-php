<?php

namespace controllers;


class Users extends \core\Controller
{
    protected $Model;

    public function __construct()
    {
        $this->Model = new \models\Users();
    }

    public function actionIndex()
    {
    }

    public function actionLogin()
    {
        if ($this->isPost()) {
            $post = $this->postFilter(['login', 'password']);
            $this->Model->Authenticate($post['login'], $post['password']);
        }
        return $this->render('login');
    }
}
