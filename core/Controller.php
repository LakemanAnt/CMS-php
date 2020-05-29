<?php

namespace core;

/**
 * Базовий клас для всіх контроллерів
 * @package core
 */
class Controller
{
    public function render($viewName, $localParams = null, $globalParams = null)
    {
        $tpl = new Template();
        if (is_array($localParams))
            $tpl->setParams($localParams);
        if (!is_array($globalParams))
            $globalParams = [];

        $moduleName = strtolower((new \ReflectionClass($this))->getShortName());
        $globalParams['Content'] = $tpl->render("views/{$moduleName}/{$viewName}.php");
        return $globalParams;
    }

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * Фільтрація асоціативного масиву
     * @param $array array Асоціативний масив
     * @param $key array Масив ключів
     * @return array Відфільтрований асоціативний масив
     */
    public function formFilter($array, $keys)
    {
        $res = [];
        foreach ($array as $key => $value) {
            if (in_array($key, $keys))
                $res[$key] = $value;
        }
        return $res;
    }
    /**
     * Фільтрація масиву POST-змінних
     * @param $keys array Ключі, які потрібно залишити
     * @return array Відфільтрований асоціативний масив
     */
    public function postFilter($keys)
    {
        return $this->formFilter($_POST, $keys);
    }
}
