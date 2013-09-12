<?php

namespace bDone\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Admin implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app["controllers_factory"];

        $app->before(function (Request $request) use ($app) {
            $route = $request->get("_route");

            if (
                false === strpos($route, "admin_")
            ) {
                return;
            }

            if (
                !$app["session"]->has("admin_authenticated") &&
                0 === strpos($route, "admin_") &&
                "admin_login" !== $route
            ) {
                return $app->redirect($app["url_generator"]->generate("admin_login"));
            }
        });

        //------------------------------------------------------------------------------------------

        $controllers->match("/login", function (Request $request) use ($app) {
            if ($request->isMethod("post") && $app["db.admin"]->isValid($request->request->get("username"), $request->request->get("password"))) {
                $app["session"]->set("admin_authenticated", true);

                return $app->redirect($app["url_generator"]->generate("admin_homepage"));
            }

            return $app["twig"]->render("admin/login.html.twig", array("error" => $request->isMethod("post")));
        })->bind("admin_login");

        //------------------------------------------------------------------------------------------

        $controllers->get("/logout", function () use ($app) {
            $app["session"]->remove("admin_authenticated");

            return $app->redirect($app["url_generator"]->generate("admin_homepage"));
        })->bind("admin_logout");

        //------------------------------------------------------------------------------------------

        $controllers->get("/", function() use ($app) {
            return $app->redirect($app["url_generator"]->generate("admin_routename"));
        })->bind("admin_homepage");

        return $controllers;
    }
}
