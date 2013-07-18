#!/usr/bin/env php
<?php

set_time_limit(0);

$app = require __DIR__ . "/app/projektneve.php";
$app->boot();

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

$console = new Application("Projekt neve", "1.0");

if (is_dir($dir = ROOT . "/src/Insolis/Command")) {
    $finder = new Finder();

    $finder->files()->name("*Command.php")->in($dir);
    $prefix = "Insolis\\Command";

    foreach ($finder as $file) {
        $ns = $prefix;

        if ($relativePath = $file->getRelativePath()) {
            $ns .= '\\'.strtr($relativePath, '/', '\\');
        }

        $r = new \ReflectionClass($ns . "\\" . $file->getBasename(".php"));

        if ($r->isSubclassOf("Symfony\\Component\\Console\\Command\\Command") && !$r->isAbstract()) {
            /** @noinspection PhpParamsInspection */
            $console->add($r->newInstance($app));
        }
    }
}

$console->run();
