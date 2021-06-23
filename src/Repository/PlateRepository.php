<?php

namespace App\Repository;

use App\Entity\Plate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plate[]    findAll()
 * @method Plate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plate::class);
    }

    // /**
    //  * @return Plate[] Returns an array of Plate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function getPlateByIndredients($value): Array
    {
        if(count($value) < 2) {
            return [];
        }
        $query =  $this->createQueryBuilder('p')
            ->join('p.ingredients', 'ingredient');
        foreach($value as $id) {
            $query->andWhere('ingredient.id = :val')
            ->setParameter('val', $id);
        }
        return $query->getQuery()
                    ->getResult()
        ;
    }

    public function getPlateByIndredientsUnique($value): Array
    {
        $baseArray = [];

        foreach($value as $i => $id) {
            $compareArray = [];
            $query =  $this->createQueryBuilder('p')
                ->join('p.ingredients', 'ingredient')
                ->andWhere('ingredient.id = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->getResult();
            foreach($query as $plate) {
                if($i === 0) {
                    array_push($baseArray, $plate);
                    continue;
                }
                array_push($compareArray, $plate);

            }
            if($i === 0) continue;
            foreach($baseArray as $plateBase) {
                if(!in_array($plateBase, $compareArray)) {
                    unset($baseArray[array_search($plateBase,$baseArray)]);
                }
            }
        }

        
        return $baseArray;
    }
}