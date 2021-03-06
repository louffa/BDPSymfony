<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compte[]    findAll()
 * @method Compte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compte::class);
    }

    // /**
    //  * @return Compte[] Returns an array of Compte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($numeroCompte): ?Compte
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.numeroCompte = :numeroCompte')
            ->setParameter('numeroCompte', $numeroCompte)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/
    
    
    public function getMaxId()
    {
        $query = $this->createQueryBuilder('s');
        $query->select('MAX(s.id)');

        $array =  $query->getQuery()->getSingleResult();
        if($array == NULL){
            $id = 1;
        }else{
            $array[1]++;
            $id = $array[1];
        }
        $numero = "BDP_".date('d').date('m').date('Y')."_C".$id;
        return $numero;
        //return $query->getQuery()->getSingleResult();
    }

    public function getIdCpt($numeroCompte)
    {
        $query = $this->createQueryBuilder('s');
        $query->select('s')
        //->from(\Compte::class, 's')
        ->where('s.numeroCompte= :numeroCompte')
        
        ->setParameter('numeroCompte', $numeroCompte);
        
    $querys = $query->getQuery();
    foreach ($querys->getResult() as $compte){
        return $compte->getId();
    }
        //return $query->getQuery()->getSingleResult();
    }
   
}
