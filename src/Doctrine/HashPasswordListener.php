<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-19
 * Time: 23:26
 */

namespace App\Doctrine;


use App\Entity\User\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * HashPasswordListener constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity instanceof User){
            return;
        }

        $this->encodePassword($entity);

    }

    public function preUpdate(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity instanceof User){
            return;
        }

        $this->encodePassword($entity);

        //tells doctrine we updated the entity
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(User::class);
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);

    }

    public function getSubscribedEvents() {
        return ['prePersist', 'preUpdate'];
    }

    /**
     * @param $entity User
     */
    public function encodePassword(User $entity): void {
        if($entity->getPlainPassword() === null){return;}
        $encoded = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }

}