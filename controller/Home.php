<?php


/**
 * Class Home
 *
 * use to show the home page
 */
class Home extends Controller
{
    public function display($request)
    {
        $params['personalProduct'] = 0;

        $params['registrationWaitings'] = [];
        
        foreach(PERSON_CONNECTED['children'] as $children) {

            $datas = $this->cURL(API.'product/productPersonal/'.$children['childId'], 'PHP_CALL', '', 'GET');
            if($datas) $params['personalProduct'] = 1;
            $productPersos[$children['firstname'].' '.$children['lastname'].'|'.$children['childId']] = $datas;

            $waiting = $this->cURL(API.'registration/child-list/'.$children['childId'].'/waiting', 'PHP_CALL', '', 'GET');
            if($waiting)  $params['registrationWaitings'][$children['childId']] = $waiting;
        }

        (isset($productPersos)) ? $params['productPersos'] = $productPersos : $params['productPersos'] = null;
		
        $this->renderWithData('render/home/home', $params);
    }

    public function displayJO2024($request)
    {
        $this->renderHtml('render/home/mapJo2024', []);
    }

}