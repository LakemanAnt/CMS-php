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
}
