<?php

namespace controllers;

class Site
{
    public function actionIndex()
    {
        $result = [
            'Title' => 'title',
            'Content' => 'content'
        ];
        return $result;
    }
}
