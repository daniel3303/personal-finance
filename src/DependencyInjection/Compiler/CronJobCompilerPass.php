<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-04
 * Time: 16:38
 */

namespace App\DependencyInjection\Compiler;


use App\Contracts\CronJob\CronJobManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CronJobCompilerPass implements CompilerPassInterface {

    /**
     * You can modify the container here before it is dumped to PHP code.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container) : void {
        // always first check if the primary service is defined
        if (!$container->has(CronJobManagerInterface::class)) {
            return;
        }

        $definition = $container->findDefinition(CronJobManagerInterface::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('app.cron_job');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('registerCronJob', [
                new Reference($id)
            ]);
        }
    }
}