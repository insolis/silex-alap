<?php

use Insolis\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__ . "/bootstrap.php";

$app->before(function (Request $request) use ($app) {

    if ($request->request->has("fbdata")) {
        $data = $request->request->get("fbdata");

        if (isset($data["user_id"])) {
            $app["session"]->set("user_id", $data["user_id"]);
        }
    }

    $route = $request->get("_route");
    if (
        !$app["session"]->has("user_id") &&
        0 !== strpos($route, "admin_") &&
        0 !== strpos($route, "_profiler") &&
        0 !== strpos($route, "_wdt") &&
        !in_array($request->get("_route"), array("homepage", "fb_addhandler"))
    ) {
        $app["session"]->set("auth_redirect_url", $request->getRequestUri());
        return new Response("<script type='text/javascript'>top.location = '" . $app["fb"]->getAuthorizationUrl() . "';</script>");
    }
});

//--------------------------------------------------------------------------------------------------

$app->match("/fb_addhandler", function (Request $request) use ($app) {
    if (!$request->request->has("fbdata")) {
        return $app->redirect($app["url_generator"]->generate("homepage"));
    }

    $data = $request->request->get("fbdata");

    try {
        $app["felhasznalo"]->insert(array(
            "id"    =>  $data["id"],
        ));
    }
    catch (Exception $e) {
        // mar volt ilyen userid, ilyenkor semmi gond, ujra engedelyezett a juzer
    }

    return $app->redirect($app["url_generator"]->generate("homepage"));
})->bind("fb_addhandler");

//--------------------------------------------------------------------------------------------------

$app->match("/", function () use ($app) {
    return $app["twig"]->render("homepage.html.twig");
})->bind("homepage");

//--------------------------------------------------------------------------------------------------

$app->after(function (Request $request, Response $response){
    $response->headers->set("P3P", 'CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
});

$app->mount("/admin", new Insolis\Controller\Admin());

return $app;
