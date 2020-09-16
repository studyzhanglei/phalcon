<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;
use Phalcon\Url as UrlResolver;

class UrlProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('url', function () {
            $config = $this->getConfig();

            $url = new UrlResolver();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        });
    }
}