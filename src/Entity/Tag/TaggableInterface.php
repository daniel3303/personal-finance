<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 03/03/2020
 * Time: 00:49
 */

namespace App\Entity\Tag;


use Doctrine\Common\Collections\Collection;

interface TaggableInterface {
    public function getTags(): Collection;

    public function addTag(Tag $tag): void;

    public function removeTag(Tag $tag): void;
}