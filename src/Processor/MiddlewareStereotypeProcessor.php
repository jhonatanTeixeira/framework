<?php


namespace Vox\Framework\Processor;


use PhpBeans\Processor\AbstractStereotypeProcessor;
use Slim\App;
use Vox\Framework\Behavior\Middleware;

class MiddlewareStereotypeProcessor extends AbstractStereotypeProcessor
{

    public function getStereotypeName(): string
    {
        return Middleware::class;
    }

    public function process($stereotype)
    {
        /* @var $app App */
        $app = $this->getContainer()->get(App::class);

        $app->add($stereotype);
    }
}