<?php

namespace Application\Repository;

/**
 * Director
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Director extends \Doctrine\ORM\EntityRepository
{
    public function getTotal()
    {
        $query = $this->_em->createQueryBuilder()
                ->select('count(d.id)')
                ->from('Application\Entity\Director','d');
        $queryResult  = $query->getQuery();
    return (int) $queryResult->getSingleScalarResult();
    }
    
    public function existeAlgunDirector()
    {
        $total = $this->getTotal();
        return $total > 0;
    }
}
