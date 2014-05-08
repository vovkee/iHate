<?php

namespace ihate\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    /**
     * @param QueryBuilder $query
     * @param User $user
     * @return QueryBuilder
     */
    private function setUserAndFollowersQuery(QueryBuilder $query, User $user)
    {
        $ids = $this->getFollowersIds($user->getFollowers());
        return $query->join('p.user', 'u')
            ->leftJoin('p.hates', 'h')
            ->leftJoin('h.user', 'hu')
            ->where('p.user IN (:follow)')
            ->orWhere('p.user = :user')
            ->orWhere('h.user = :user')
            ->orWhere('h.user IN (:follow)')
            ->setParameter('user', $user)
            ->setParameter('follow', $ids);
    }

    /**
     * @param User $user
     * @return integer
     */
    public function getCountByUserAndFollowers(User $user)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)');

        return $this->setUserAndFollowersQuery($query, $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param User $user
     * @param integer $page
     * @param integer $perPage
     * @return array
     */
    public function getByUserAndFollowers(User $user, $page, $perPage)
    {
        $first = ($page-1)*$perPage;

        $query = $this->createQueryBuilder('p')
            ->addSelect('u, h, hu');
        return $this->setUserAndFollowersQuery($query, $user)
            ->setFirstResult($first)
            ->setMaxResults($perPage)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function getFollowersIds($users)
    {
        $ids = array();
        foreach ($users as $user) {
            $ids[] = $user->getId();
        }
        return $ids;
    }

    public function getMyPosts(User $user)
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function getPostTop(User $user)
    {
        $country = $user->getCountry();
        $results = $this->createQueryBuilder('p')
            ->addSelect('COUNT(h) AS hates')
            ->join('p.hates', 'h')
            ->leftjoin('h.user','u')
            ->where('u.country = :country')
            ->setParameter('country', $country)
            ->groupBy('p')
            ->orderBy('hates', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->setMaxResults(5)
            ->getResult();
        $posts = array();
        foreach ($results as $post) {
            $posts[] = $post[0];
        }
        return $posts;
    }
}
