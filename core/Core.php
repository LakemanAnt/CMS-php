<?php

namespace core;

/**
 * Головний клас ядра системи
 */
class Core
{
    private static $instance;
    private $mainTemplate;
    private $DB;

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
     * Повертає об'єкт для роботи з базою даних
     * @return DB об'єкт для роботи з базою даних
     */
    public function getDB()
    {
        return $this->DB;
    }
    /**
     * Ініціалізація системи
     */
    public function init()
    {
        global $CMSConfig;
        session_start();
        spl_autoload_register('\core\Core::__autoload');
        $this->mainTemplate = new Template();
        $this->DB = new \core\DB(
            $CMSConfig['Database']['Server'],
            $CMSConfig['Database']['User'],
            $CMSConfig['Database']['Password'],
            $CMSConfig['Database']['DatabaseName']

        );
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
                    $this->mainTemplate->setParams($result);
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
        $this->mainTemplate->display('views/layout/index.php');
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
