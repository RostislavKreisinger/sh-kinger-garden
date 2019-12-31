<?php


namespace Shopsys\ShopBundle\Model\AdvancedSearch;

use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductAvailabilityFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductBrandFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductCalculatedSellingDeniedFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductCategoryFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductCatnumFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductFlagFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductNameFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductPartnoFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\Filter\ProductStockFilter;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\ProductAdvancedSearchConfig as ProductAdvancedSearchConfigFramework;
use Shopsys\ShopBundle\Model\AdvancedSearch\Filter\ProductEANFilter;

class ProductAdvancedSearchConfig extends ProductAdvancedSearchConfigFramework
{

    /**
     * ProductAdvancedSearchConfig constructor.
     * @param ProductCatnumFilter $productCatnumFilter
     * @param ProductNameFilter $productNameFilter
     * @param ProductPartnoFilter $productPartnoFilter
     * @param ProductStockFilter $productStockFilter
     * @param ProductFlagFilter $productFlagFilter
     * @param ProductCalculatedSellingDeniedFilter $productCalculatedSellingDeniedFilter
     * @param ProductAvailabilityFilter $productAvailabilityFilter
     * @param ProductBrandFilter $productBrandFilter
     * @param ProductCategoryFilter $productCategoryFilter
     * @param ProductEANFilter $productEANFilter
     * @throws \Shopsys\FrameworkBundle\Model\AdvancedSearch\Exception\AdvancedSearchFilterAlreadyExistsException
     */
    public function __construct(
        ProductCatnumFilter $productCatnumFilter,
        ProductNameFilter $productNameFilter,
        ProductPartnoFilter $productPartnoFilter,
        ProductStockFilter $productStockFilter,
        ProductFlagFilter $productFlagFilter,
        ProductCalculatedSellingDeniedFilter $productCalculatedSellingDeniedFilter,
        ProductAvailabilityFilter $productAvailabilityFilter,
        ProductBrandFilter $productBrandFilter,
        ProductCategoryFilter $productCategoryFilter,
        ProductEANFilter $productEANFilter
    ) {
        parent::__construct(
            $productCatnumFilter,
            $productNameFilter,
            $productPartnoFilter,
            $productStockFilter,
            $productFlagFilter,
            $productCalculatedSellingDeniedFilter,
            $productAvailabilityFilter,
            $productBrandFilter,
            $productCategoryFilter
        );

        $this->registerFilter($productEANFilter);

    }


}