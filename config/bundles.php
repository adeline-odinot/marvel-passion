<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['dev' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],

    App\MarvelPassion\ArticleBundle\ArticleBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\CommentBundle\CommentBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\ContactBundle\ContactBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\DashboardBundle\DashboardBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\HomeBundle\HomeBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\LegalNoticeBundle\LegalNoticeBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\ShootingBundle\ShootingBundle::class => ['dev' => true, 'test' => true],
    App\MarvelPassion\UserBundle\UserBundle::class => ['dev' => true, 'test' => true],
];
