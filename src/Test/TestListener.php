<?php


namespace Vox\Framework\Test;


use Metadata\MetadataFactory;
use PhpBeans\Annotation\Autowired;
use PhpBeans\Factory\ContainerBuilder;
use PhpBeans\Metadata\ClassMetadata;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use Prophecy\Prophet;
use Throwable;
use Vox\Framework\Application;
use Vox\Framework\Test\Behavior\Mock;
use Vox\Metadata\Factory\MetadataFactoryFactory;

class TestListener implements \PHPUnit\Framework\TestListener
{
    private ?MetadataFactory $metadataFactory = null;
    private ?Prophet $prophet = null;
    private ?Application $application = null;

    public function addError(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addError() method.
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        // TODO: Implement addWarning() method.
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        // TODO: Implement addFailure() method.
    }

    public function addIncompleteTest(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addIncompleteTest() method.
    }

    public function addRiskyTest(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addRiskyTest() method.
    }

    public function addSkippedTest(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addSkippedTest() method.
    }

    public function startTestSuite(TestSuite $suite): void
    {
        $this->metadataFactory = (new MetadataFactoryFactory)
            ->createAnnotationMetadataFactory(ClassMetadata::class);
        $this->prophet = new Prophet();
    }

    public function endTestSuite(TestSuite $suite): void
    {
        // TODO: Implement endTestSuite() method.
    }

    public function startTest(Test $test): void
    {
        if (!$test instanceof TestCase) {
            return;
        }

        $test->setProphet($this->prophet);

        /* @var $metadata ClassMetadata */
        $metadata = $this->metadataFactory->getMetadataForClass(get_class($test));
        $app = new Application();
        $app->configure(fn(ContainerBuilder $builder) => $builder->withAppNamespaces());
        $mocks = [];
        $beans = [];

        foreach ($metadata->getAnnotatedProperties(Mock::class) as $propertyMetadata) {
            $annotation = $propertyMetadata->getAnnotation(Mock::class);
            $type = $annotation->type;
            $serviceId = $annotation->serviceId ?? $type;
            $mocks[$serviceId] = $mock = $this->prophet->prophesize($type);
            $beans[$serviceId] = $mock->reveal();
            $propertyMetadata->setValue($test, $mock);
        }

        $app->getBuilder()->withBeans($beans);

        $test->setApplication($app);

        foreach ($metadata->getAnnotatedProperties(Autowired::class) as $propertyMetadata) {
            $autowired = $propertyMetadata->getAnnotation(Autowired::class);
            $id = $autowired->beanId ?? $propertyMetadata->type;
            $propertyMetadata->setValue($test, $app->getContainer()->get($id));
        }
    }

    public function endTest(Test $test, float $time): void
    {
        // TODO: Implement endTest() method.
    }
}