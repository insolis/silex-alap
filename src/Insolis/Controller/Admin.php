<?php

namespace Insolis\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Admin implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app["controllers_factory"];

        $app->before(function (Request $request) use ($app) {
            if (
                false === strpos($request->get("_route"), "admin_") ||
                in_array($request->get("_route"), array("admin_login", "admin_logout"))
            ) {
                return;
            }

            if (!$app["session"]->has("admin_authenticated")) {
                return $app->redirect($app["url_generator"]->generate("admin_login"));
            }
        });

        //------------------------------------------------------------------------------------------

        $controllers->get("/", function() use ($app) {
            return $app->redirect($app["url_generator"]->generate("admin_routename"));
        })->bind("admin_homepage");

        //------------------------------------------------------------------------------------------

        $controllers->match("/login", function(Request $request) use ($app) {
            $error = "";

            if ($request->isMethod("post")) {
                if ($app["admin"]->isValid($request->request->get("username"), $request->request->get("password"))) {
                    $app["session"]->set("admin_authenticated", true);
                    return $app->redirect($app["url_generator"]->generate("admin_homepage"));
                }
                $error = "Hibás felhasználónév és/vagy jelszó";
            }

            return $app["twig"]->render("admin/login.html.twig", array("error" => $error));
        })->bind("admin_login");

        //------------------------------------------------------------------------------------------

        $controllers->get("/logout", function() use ($app) {
            $app["session"]->remove("admin_authenticated");
            return $app->redirect($app["url_generator"]->generate("admin_login"));
        })->bind("admin_logout");

        return $controllers;
    }
}
