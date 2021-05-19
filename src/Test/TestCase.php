<?php


namespace Vox\Framework\Test;


use PHPUnit\Framework\TestCase as BaseTestCase;
use Vox\Framework\Application;

class TestCase extends BaseTestCase
{
    protected ?Application $application = null;

    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }
}