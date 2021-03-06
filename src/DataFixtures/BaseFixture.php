<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 19:53
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;
use http\Exception\RuntimeException;

abstract class BaseFixture extends Fixture {
    private array $referencesIndex = [];

    /** @var Generator */
    protected Generator $faker;

    /** @var ObjectManager */
    protected ObjectManager $manager;

    public function __construct() {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager) :void {
        $this->manager = $manager;
        $this->loadData($manager);
        $manager->flush();
    }

    abstract protected function loadData(ObjectManager $manager);

    protected function createMany(string $className, int $count, callable $factory)  :void {
        for ($i = 0; $i < $count; $i++) {
            $entity = $factory($i);
            if (!$entity || get_class($entity) !== $className) {
                throw new RuntimeException('Invalid entity class ' . get_class($entity) . ' expected ' . $className);
            }
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }

    /**
     * @param string $className
     * @return object
     * @throws Exception
     */
    protected function getRandomReference(string $className) {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className . '_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new RuntimeException(sprintf('Cannot find any references for class "%s"', $className));
        }
        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);
        return $this->getReference($randomReferenceKey);
    }
}