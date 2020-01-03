<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Article;

use Doctrine\ORM\QueryBuilder;
use Shopsys\FrameworkBundle\Component\Paginator\PaginationResult;
use Shopsys\FrameworkBundle\Component\Paginator\QueryPaginator;
use Shopsys\FrameworkBundle\Model\Article\ArticleFacade as ArticleFacadeFramework;

class ArticleFacade extends ArticleFacadeFramework
{
    public const DEFAULT_PAGE_LIMIT = 5;

    /**
     * @param int $domainId
     * @param int $count
     * @return array
     */
    public function getHomepageArticlesByDomain(int $domainId, int $count): array
    {
        $queryBuilder = $this->getHomepageArticleQueryBuilder($domainId);
        $queryBuilder->setMaxResults($count);
        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @param int $domainId
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getHomepageArticleQueryBuilder(int $domainId): QueryBuilder
    {
        return $this->articleRepository->getOrderedArticlesByDomainIdAndPlacementQueryBuilder($domainId, Article::PLACEMENT_HOMEPAGE);
    }

    /**
     * @param int $domainId
     * @param int $page
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getHomepageArticlesForListPaginator(int $domainId, int $page): PaginationResult
    {
        $queryBuilder = $this->getHomepageArticleQueryBuilder($domainId);
        return $this->createPaginationResultWithData($queryBuilder, $page);
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param int $page
     * @param int $limit
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    protected function createPaginationResultWithData(QueryBuilder $queryBuilder, int $page, int $limit = self::DEFAULT_PAGE_LIMIT): PaginationResult
    {
        $queryPaginator = new QueryPaginator($queryBuilder);
        return $queryPaginator->getResult($page, $limit);
    }
}
