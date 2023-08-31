<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function save(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNumberOfReplies($article)
    {

        $conn = $this->getEntityManager()->getConnection();

        /* $qb = $this->createQueryBuilder('qb');
        $query = $qb->select('identity(c.replies_to), COUNT(c.replies_to)')
            ->from(Comments::class, 'c')
            ->where('c.from_article = :from_article')
            ->groupBy('c.replies_to')
            ->setParameter('from_article', $article)
            ->getQuery(); */

        //$qb->getDQL();
        //dd($query->getSQL());

        //SELECT c.replies_to_id, COUNT(c.replies_to_id) FROM comments AS c WHERE c.from_article_id = 3 AND c.replies_to_id IS NOT NULL GROUP BY c.replies_to_id

        $sql = '
            SELECT
                c.replies_to_id AS comment_id,
                COUNT(c.replies_to_id) AS number_of_replies
            FROM comments AS c
            WHERE
                c.from_article_id = :article_id
                AND c.replies_to_id IS NOT NULL
            GROUP BY c.replies_to_id
            ';

        $result = $conn->executeQuery($sql, ['article_id' => $article->getId()]);
        $result = $result->fetchAllAssociative();
        $rearangedResult = [];
        foreach ($result as $key => $value) {
            $rearangedResult[$value['comment_id']] = $value['number_of_replies'];
        }
        //dd($result->fetchAllAssociative());
        return $rearangedResult;

    }

//    /**
//     * @return Comments[] Returns an array of Comments objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comments
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
