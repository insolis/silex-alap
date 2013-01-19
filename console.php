#!/usr/bin/env php
<?php

set_time_limit(0);

$app = require __DIR__ . "/app/teruletfoglalas.php";
$app->boot();

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application("Projekt neve", "1.0");

/* az alabbiak azert maradtak csak benn, hogy latszodjon, hogy kell parancssoros taskot kesziteni
a pelda meghivasa: php console.php pontok

$console->register("pontok")
  ->setDescription("Pontok novelese")
  ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
    $pontok = $app["pont"]->findAll();

    foreach ($pontok as $pont) {
        if ($pont["tulajdonos"] && !in_array($pont["tulajdonos"], $app["kizart_jatekosok"])) {
            $dt = \DateTime::createFromFormat("Y-m-d H:i:s", $pont["elfoglalas_ideje"]);

            if ($dt->modify("+1 day") < new \DateTime()) {
                $pont["tulajdonos"] = false;

                $app["pont"]->update(array("tulajdonos" => null), array("id" => $pont["id"]));
            }
        }

        if ($pont["tulajdonos"]) {
            $app["felhasznalo"]->pontokMentese($pont["tulajdonos"], 1);
        }
    }
  });
*/

$console->run();
