<?php
require 'vendor/autoload.php';
$kernel = new \App\Kernel($_ENV['APP_ENV'] ?? 'dev', $_ENV['APP_DEBUG'] ?? false);
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine.orm.entity_manager');

// Create test competences
$competence1 = new \App\Entity\Competence();
$competence1->setName('PHP Programming');
$competence1->setCategory('technique');
$competence1->setMaxLevel(5);
$competence1->setDescription('Advanced PHP development skills including OOP, design patterns, and frameworks');
$em->persist($competence1);

$competence2 = new \App\Entity\Competence();
$competence2->setName('Problem Solving');
$competence2->setCategory('comportementale');
$competence2->setMaxLevel(4);
$competence2->setDescription('Ability to identify, analyze, and solve complex problems efficiently');
$em->persist($competence2);

$competence3 = new \App\Entity\Competence();
$competence3->setName('English Communication');
$competence3->setCategory('langue');
$competence3->setMaxLevel(5);
$competence3->setDescription('Professional English speaking and writing skills');
$em->persist($competence3);

$em->flush();
echo "✅ 3 test competences created\n";
