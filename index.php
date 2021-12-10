<?php

require 'vendor/autoload.php';

use Hellokant\Factory\ConnectionFactory;
use Hellokant\Model\Article;
use Hellokant\Model\Categorie;

$conf = parse_ini_file('conf/db.conf.ini');
ConnectionFactory::makeConnection($conf);

$article = new Article();
$article->nom = 'planche à voile';
$article->descr = 'Une planche à voile pour surfer sur la vague';
$article->tarif = 35.99;
$article->id_categ = 1;
$article->categorie;

$categorie = Categorie::first(1);
$categorie->articles;


Article::all();
Article::find(106, ['tarif', 'id']);
Article::find(['nom', 'like', 'roller'], ['nom', 'tarif']);
Article::first(['tarif', '=', 12.5]);
