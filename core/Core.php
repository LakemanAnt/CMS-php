<?php

namespace core;

/**
 * Головний клас ядра системи
 */
class Core
{
    private static $instance;
    private static $mainTemplate;
    private function __construct()
    {
    }
    /**
     * Повертає екземпляр ядра системи
     * @return Core
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Core();
            return self::getInstance();
        } else {
            return self::$instance;
        }
    }
    /**
     * Ініціалізація системи
     */
    public function init()
    {
        session_start();
        spl_autoload_register('\core\Core::__autoload');
        self::$mainTemplate = new Template();
    }
    /**
     * Виконує основний процес роботи CMS-системи
     */
    public function run()
    {
        $path = $_GET['path'];
        $pathParts = explode('/', $path);
        $className = ucFirst($pathParts[0]);
        if (empty($className)) {
            $fullClassName = 'controllers\\Site';
        } else {
            $fullClassName = 'controllers\\' . $className;
        }
        $methodName = ucFirst($pathParts[1]);
        if (empty($methodName)) {
            $fullMethodName = 'actionIndex';
        } else {
            $fullMethodName = 'action' . $methodName;
        }

        if (class_exists($fullClassName)) {
            $controller = new $fullClassName();

            if (method_exists($controller, $fullMethodName)) {
                $method = new \ReflectionMethod($fullClassName, $fullMethodName);
                $paramsArray = [];
                foreach ($method->getParameters() as $parameter) {
                    array_push($paramsArray, isset($_GET[$parameter->name]) ? $_GET[$parameter->name] : null);
                }
                $result = $method->invokeArgs($controller, $paramsArray);

                if (is_array($result)) {
                    self::$mainTemplate->setParams($result);
                }
            } else {
                throw new \Exception('404 Not Found');
            }
        } else {
            throw new \Exception('404 Not Found');
        }

        //echo "Class : {$className}, methos : {$fullMethodName}";
    }
    /**
     * Завершення роботи системи та виведення результату
     */
    public function done()
    {
        self::$mainTemplate->display('views/layout/index.php');
    }
    /**
     * Автозавантаження класів
     * @param $className string Назва класу
     */
    public static function __autoload($className)
    {
        $fileName = $className . '.php';
        if (is_file($fileName)) {
            include($fileName);
        }
    }
}
