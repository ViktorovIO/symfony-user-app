<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function searchByQuery(array $queryList)
    {
        $qb = $this->createQueryBuilder('u');

        if (isset($queryList['last_name'])) {
            $qb->andWhere('u.lastName LIKE :last_name')
                ->setParameter('last_name', "%{$queryList['last_name']}%");
        }

        if (isset($queryList['first_name'])) {
            $qb->andWhere('u.firstName LIKE :first_name')
                ->setParameter('first_name', "%{$queryList['first_name']}%");
        }

        if (isset($queryList['surname'])) {
            $qb->andWhere('u.surname LIKE :surname')
                ->setParameter('surname', "%{$queryList['surname']}%");
        }

        if (isset($queryList['phone_list'])) {
            $qb->andWhere('CAST(u.phoneList AS TEXT) LIKE :phone_list')
                ->setParameter('phone_list', "%{$queryList['phone_list']}%");
        }

        if (isset($queryList['phone_count'])) {
            $qb->andWhere('JSON_ARRAY_LENGTH(u.phoneList) >= :phone_count')
                ->setParameter('phone_count', (int) $queryList['phone_count']);
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
