<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Front;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\ShopBundle\Grid\Article\ArticleGridFactory;
use Shopsys\ShopBundle\Model\Article\Article;
use Shopsys\ShopBundle\Model\Article\ArticleFacade;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends FrontBaseController
{
    /**
     * @var \Shopsys\ShopBundle\Model\Article\ArticleFacade
     */
    private $articleFacade;

    /**
     * @var \Shopsys\ShopBundle\Grid\Article\ArticleGridFactory
     */
    private $articleGridFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * ArticleController constructor.
     * @param \Shopsys\ShopBundle\Model\Article\ArticleFacade $articleFacade
     * @param \Shopsys\ShopBundle\Grid\Article\ArticleGridFactory $articleGridFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(ArticleFacade $articleFacade, ArticleGridFactory $articleGridFactory, Domain $domain)
    {
        $this->domain = $domain;
        $this->articleFacade = $articleFacade;
        $this->articleGridFactory = $articleGridFactory;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $currentPage = (int)$request->get('page') ?? 1;

        $articlesPaginator = $this->articleFacade->getHomepageArticlesForListPaginator($this->domain->getId(), $currentPage);

        return $this->render('@ShopsysShop/Front/Content/Article/index.html.twig', [
            'articlesPaginator' => $articlesPaginator,
        ]);
    }

    /**
     * @throws \Shopsys\FrameworkBundle\Component\Grid\Exception\DuplicateColumnIdException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $articleGrid = $this->articleGridFactory->create();

        return $this->render('@ShopsysShop/Front/Content/Article/list.html.twig', [
            'articleGridView' => $articleGrid->createView(),
        ]);
    }

    /**
     * @param int $id
     */
    public function detailAction($id)
    {
        $article = $this->articleFacade->getVisibleById($id);

        return $this->render('@ShopsysShop/Front/Content/Article/detail.html.twig', [
            'article' => $article,
        ]);
    }

    public function menuAction()
    {
        $articles = $this->articleFacade->getVisibleArticlesForPlacementOnCurrentDomain(Article::PLACEMENT_TOP_MENU);

        return $this->render('@ShopsysShop/Front/Content/Article/menu.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function footerAction()
    {
        $articles = $this->articleFacade->getVisibleArticlesForPlacementOnCurrentDomain(Article::PLACEMENT_FOOTER);

        return $this->render('@ShopsysShop/Front/Content/Article/menu.html.twig', [
            'articles' => $articles,
        ]);
    }
}
