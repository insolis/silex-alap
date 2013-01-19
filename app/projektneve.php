<?php

use Insolis\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__ . "/bootstrap.php";

//--------------------------------------------------------------------------------------------------

$app->match("/fb_addhandler", function (Request $request) use ($app) {
    if (!$request->query->has("code")) {
        return $app->redirect($app["url_generator"]->generate("homepage"));
    }

    $data = $app["fb"]->getUserData();
    $app["monolog"]->addInfo("ezt kaptuk a fbtol", $data);

    // ide jon a $data feldolgozasa - user regisztracio, bejelentkeztetes - ez projektfuggo

    return $app->redirect($app["url_generator"]->generate("homepage"));
})->bind("fb_addhandler");

//--------------------------------------------------------------------------------------------------

$app->match("/", function () use ($app) {
    //
})->bind("homepage");

//--------------------------------------------------------------------------------------------------

$app->after(function (Request $request, Response $response){
    $response->headers->set("P3P", 'CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
});

$app->mount("/admin", new Insolis\Controller\Admin());

return $app;
