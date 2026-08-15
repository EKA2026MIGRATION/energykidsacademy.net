<?php

class Blog extends Controller
{
    public function List($request)
    {

        $this->renderWithData('render/blog/list', $this->cURL(API.'blog/list?page=1&size='.SIZE_LIST, 'PHP_CALL', '', 'GET'));

    }

    public function Article($request)
    {

       if(isset($request->id))
        {
            $this->renderWithData('render/blog/article', $this->cURL(API.'blog/display/'.$request->id, 'PHP_CALL', '', 'GET'));
        }
        else
        {
            header('location: '.HOST.'blog/list');
        }

    }



}
