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
            if (
                false === strpos($request->get("_route"), "admin_")
            ) {
                return;
            }

            // ha nincs email, akkor szerzunk
            if (!$app["session"]->has("admin_email")) {
                $openid = new \LightOpenID($request->server->get("SERVER_NAME"));

                if (!$openid->mode) {
                    $openid->identity = "https://www.google.com/accounts/o8/id";
                    $openid->required = ["email" => "contact/email"];

                    return $app->redirect($openid->authUrl());
                } else {
                    if ($openid->validate()) {
                        $attributes = $openid->getAttributes();
                        $app["session"]->set("admin_email", $attributes["contact/email"]);
                    }
                }
            }

            $email = $app["session"]->get("admin_email");
            if (
                false === strpos($email, "@bdone.hu") &&
                false === strpos($email, "@karmamedia.eu")
            ) {
                $app->abort(403);
            }
        });

        //------------------------------------------------------------------------------------------

        $controllers->get("/", function() use ($app) {
            return $app->redirect($app["url_generator"]->generate("admin_routename"));
        })->bind("admin_homepage");

        return $controllers;
    }
}
