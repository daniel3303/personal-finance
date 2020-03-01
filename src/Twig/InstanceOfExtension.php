<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-05-26
 * Time: 17:54
 */

namespace App\Twig;


use ReflectionClass;
use ReflectionException;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class InstanceOfExtension extends AbstractExtension {
    /**
     * Returns a list of tests to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests() : array {
        return array(
            new TwigTest('instanceOf', array($this, 'instanceOf')),
        );
    }

    /**
     * Tests if the first object is an instance of the second object type of class name
     * @param $var
     * @param $instance
     * @return bool
     */
    public function instanceOf($var, $instance) : bool {
        if(!is_object($var)) {
            return false;
        }

        if(is_object($instance)){
            $instance = get_class($instance);
        }

        try {
            $reflexionClass = new ReflectionClass($instance);
        } catch (ReflectionException $e) {
            return false;
        }
        return $reflexionClass->isInstance($var);
    }

}