<?php


namespace Shopsys\ShopBundle\Model\AdvancedSearch;

use Shopsys\FrameworkBundle\Form\Admin\AdvancedSearch\AdvancedSearchProductFilterTranslation as AdvancedSearchProductFilterTranslationFramework;
use Shopsys\ShopBundle\Model\AdvancedSearch\Filter\ProductEANFilter;

class AdvancedSearchProductFilterTranslation extends AdvancedSearchProductFilterTranslationFramework
{

    public function __construct()
    {

        parent::__construct();

        $this->addFilterTranslation(ProductEANFilter::NAME, t('Product EAN'));
    }

}