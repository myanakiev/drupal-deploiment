<?php

//http://user8.d8.lab/hello.json

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class HelloJSONController extends ControllerBase {

    public function getContent() {
        //http://php.net/manual/fr/function.json-decode.php
        //http://symfony.com/doc/current/components/http_foundation.html#response
        
        // on peut utiliser JSONResponse
        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        $response->setCharset('UTF-8');
        $response->setContent(json_encode(array('do', 'ré', 'mi')));

        return $response;
    }

}
