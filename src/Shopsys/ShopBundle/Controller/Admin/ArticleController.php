<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Controller\Admin\ArticleController as ArticleControllerAdmin;
use Shopsys\ShopBundle\Model\Article\Article;

class ArticleController extends ArticleControllerAdmin
{
    /**
     * @Route("/article/list/")
     */
    public function listAction()
    {
        $gridHomepage = $this->getGrid(Article::PLACEMENT_HOMEPAGE);
        $gridTop = $this->getGrid(Article::PLACEMENT_TOP_MENU);
        $gridFooter = $this->getGrid(Article::PLACEMENT_FOOTER);
        $gridNone = $this->getGrid(Article::PLACEMENT_NONE);
        $articlesCountOnSelectedDomain = $this->articleFacade->getAllArticlesCountByDomainId($this->adminDomainTabsFacade->getSelectedDomainId());

        return $this->render('@ShopsysShop/Admin/Content/Article/list.html.twig', [
            'gridViewHomepage' => $gridHomepage->createView(),
            'gridViewTop' => $gridTop->createView(),
            'gridViewFooter' => $gridFooter->createView(),
            'gridViewNone' => $gridNone->createView(),
            'articlesCountOnSelectedDomain' => $articlesCountOnSelectedDomain,
        ]);
    }
}
