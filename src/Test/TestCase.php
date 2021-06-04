<?php


namespace Vox\Framework\Test;


use PHPUnit\Framework\TestCase as BaseTestCase;
use Prophecy\Prophet;
use Vox\Framework\Application;

class TestCase extends BaseTestCase
{
    protected ?Application $application = null;
    protected ?Prophet $prophet = null;

    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }

    public function setProphet(Prophet $prophet)
    {
        $this->prophet = $prophet;
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }
}