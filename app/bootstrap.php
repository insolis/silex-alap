<?php

require __DIR__ . "/../vendor/autoload.php";

define("ROOT", __DIR__ . "/../");

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    "monolog.logfile" => ROOT . "/cache/app.log",
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider(), array(
    "session.storage.options" => array(
        "name" => "projekt_neve",
    ),
));

$app["session.storage.handler"] = $app->share(function () use ($app) {
    return new Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler(
        $app["db"]->getWrappedConnection(),
        array(
            "db_table"  =>  "projektneve_session",
        )
    );
});

$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.transport'] = $app->share(function () use ($app) {
    $invoker = new \Swift_Transport_SimpleMailInvoker();

    $transport = new \Swift_Transport_MailTransport(
        $invoker,
        $app["swiftmailer.transport.eventdispatcher"]
    );

    return $transport;
});

$app->register(new Silex\Provider\TranslationServiceProvider(), array("translator.messages" => array()));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    "twig.options"    =>  array(
        "cache"       =>  __DIR__ . "/../cache/twig/",
        "auto_reload" => true,
    ),
    "twig.path" => array(
        __DIR__ . "/../src/Resources/views",
        __DIR__ . "/../vendor/symfony/twig-bridge/Symfony/Bridge/Twig/Resources/views/Form",
    ),
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());


$app->register(new Insolis\Provider\RepositoryServiceProvider(), array("repository.repositories" => array(
    "db.admin"      =>  'bDone\Repository\Admin',
)));

$app->register(new Insolis\Provider\FacebookServiceProvider());

if (!file_exists(__DIR__ . "/config.php")) {
    throw new Exception("Hianyzik a config.php");
}

require __DIR__ . "/config.php";

if ($app["debug"]) {
    Symfony\Component\Debug\Debug::enable(E_ALL, true);

    $app->register($p = new Silex\Provider\WebProfilerServiceProvider(), array(
        "profiler.cache_dir" => __DIR__ . "/../cache/profiler/"
    ));

    $app->mount("_profiler", $p);
}

$app["session"]->start();

$app["twig"] = $app->share($app->extend("twig", function (\Twig_Environment $twig, Silex\Application $app) {
    $twig->addGlobal("fb", $app["fb.options"]);

    return $twig;
}));

return $app;
