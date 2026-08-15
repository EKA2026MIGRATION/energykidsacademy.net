<?php
/**
 * Class Routeur
 *
 * create routes and find controller
 */

class Routeur
{
    private $url;
    private $routes;
    private $request;
    public function __construct($url)
    {
        $routes = parse_ini_file('routes/routes.ini', true);
        $this->routes = $routes;
        $this->url = $url;
        $route  = $this->getRoute();
        $params = $this->getParams();
        $request = new Request();
        $request->setRoute($route);
        $request->setParams($params);
        $this->request = $request;
    }
    public function getRoute()
    {
        $elements = explode('/', $this->url);
        if(isset($elements[1]))
        {
            return $elements[0].'/'.$elements[1];
        }
        else
        {
            return $elements[0];            
        }

    }
    public function getParams()
    {
        $params = array();
        // extract GET params
        $elements = explode('/', $this->url);


        
        if(isset($elements[1]))
        {
            unset($elements[0]);
            unset($elements[1]);
            $firstI = 2;
        }
        else
        {
            unset($elements[0]);
            $firstI = 1;
        }

        for($i = $firstI; $i<count($elements); $i++)
        {
            $params[$elements[$i]] = $elements[$i+1];
            $i++;
        }

        if(isset($params['iframe']))
        {
            define('IFRAME', 1);
        }
        else
        {
            define('IFRAME', 0);
        }
        // extract POST params
        if($_POST)
        {
            foreach($_POST as $key => $val)
            {
                $params[$key] = $val;
            }
        }
        return $params;
    }
    public function renderController()
    {
        $request = $this->request;


        if(key_exists($request->getRoute(), $this->routes))
        {
            $controller = $this->routes[$request->getRoute()]['controller'];
            $method     = $this->routes[$request->getRoute()]['method'];
            $security     = $this->routes[$request->getRoute()]['security'];
            $security = explode(',', $security);


            // Vérification des accès
            if (in_array(ROLE, $security) OR in_array("ALL", $security)) {
          
                $currentController = new $controller();
                $requestObjets =  (object) $request->getParams();
                $currentController->$method($requestObjets);

            }
            else
            {
                $currentController = new ErrorPage();
                $currentController->showAccess();
           
            }
        } else {
            $currentController = new ErrorPage();
            $currentController->show404();

        }
    }

}