<?php

/**
 * Class View
 * organize the view
 */
class View
{

    private $template;
    private $App = 0;

    /**
     * set the template.
     * @param null $template
     * @return $this
     */
    public function setTemplate($template)
    {
        if(preg_match("/app/i", $template)) {
            $this->App = 1;
            $el = explode('/', $template);
            $template = $el[1];
        }

        $this->template = $template;
        return $this;
    }

    /**
     * render the template
     * @param array $params
     */
    public function render($params)
    {

        $template = $this->template;
        ob_start();

        if($this->App == 1)
        {
            include(APPLICATION.'pages/'.$template.'.php');

        } else {
            include(VIEW.$template.'.php');
        }
        $contentPage = ob_get_clean();

        if(ROUTE == "ea/cart" OR ROUTE == "ea/loadProductALacarte"  OR ROUTE == "ea/connectApp")
        {
            include_once (VIEW.'template/cart.php');
        }
        elseif(ROUTE == "ea/cartFromApp")
        {
            include_once (VIEW.'template/cartFromApp.php');  
        }
        else
        {
            include_once (VIEW.'template/template.php');
        }



    }

    /**
     * render the view without base template
     * @param array $params
     */
    public function renderWithoutTemplate($params)
    {
        $template = $this->template;
        include(VIEW.$template.'.php');
    }


    /**
     * redirect to the route
     * @param $route
     */
    public function redirect($route)
    {
        header("Location: ".HOST.$route);
        exit;
    }

}
