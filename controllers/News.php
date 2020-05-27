<?php

namespace controllers;

use core\Controller;

/**
 * Контроллер для модуля News
 * @param controllers
 */
class News extends Controller
{
    /**
     * Відображення початкової сторінки модуля
     */
    public function actionIndex()
    {
        return $this->render('index', ['count' => 20], ['Title' => 'News']);
    }
    /**
     * Відображення списку новин
     */
    public function actionList()
    {
        echo 'actionList';
    }
}
