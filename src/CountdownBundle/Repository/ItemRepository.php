<?php

namespace CountdownBundle\Repository;

use CountdownBundle\Entity\Item;
use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 */
class ItemRepository extends EntityRepository
{
    /**
     * create Item
     *
     * @return Item
     */
    public function create()
    {
        return new Item();
    }

    /**
     * @param Item $item
     *
     * @return object
     */
    public function merge(Item $item)
    {
        return $this->getEntityManager()->merge($item);
    }

    /**
     * @param Item $item
     */
    public function persist(Item $item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush($item);
    }
}
