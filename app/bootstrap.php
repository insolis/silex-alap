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
    "session.storage.save_path" => __DIR__ . "/../cache/sessions/",
));

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
    "twig.path" => array(
        __DIR__ . "/../src/Resources/views",
        __DIR__ . "/../vendor/symfony/twig-bridge/Symfony/Bridge/Twig/Resources/views/Form",
    ),
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());


$app->register(new Knp\Provider\RepositoryServiceProvider(), array("repository.repositories" => array(
    "db.admin"          =>  'Insolis\\Repository\\Admin',
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

$app["twig"]->addGlobal("fb", $app["fb.options"]);

return $app;
