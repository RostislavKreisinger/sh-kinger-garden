<?php


namespace Shopsys\ShopBundle\Model\AdvancedSearch\Filter;


use Doctrine\ORM\QueryBuilder;
use Shopsys\FrameworkBundle\Component\String\DatabaseSearching;
use Shopsys\FrameworkBundle\Model\AdvancedSearch\AdvancedSearchFilterInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductEANFilter  implements AdvancedSearchFilterInterface
{

    public const NAME = 'productEAN';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getAllowedOperators()
    {
        return [
            self::OPERATOR_IS,
            self::OPERATOR_AFTER,
            self::OPERATOR_CONTAINS,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getValueFormType()
    {
        return TextType::class;
    }

    /**
     * @inheritDoc
     */
    public function getValueFormOptions()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function extendQueryBuilder(QueryBuilder $queryBuilder, $rulesData)
    {
        foreach ($rulesData as $index => $ruleData) {
            if ($ruleData->value === null) {
                $searchValue = '%';
            } else {
                $searchValue = $this->prepareValueByOperator($ruleData->operator, $ruleData->value);
            }
            $dqlOperator = $this->getDqlOperator($ruleData->operator);
            $parameterName = 'productEAN_' . $index;
            $queryBuilder->andWhere('NORMALIZE(p.ean) ' . $dqlOperator . ' NORMALIZE(:' . $parameterName . ')');
            $queryBuilder->setParameter($parameterName, $searchValue);
        }
    }

    /**
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    protected function prepareValueByOperator(string $operator, $value):string{
        switch ($operator) {
            case self::OPERATOR_AFTER:
                return DatabaseSearching::getLikeSearchString($value) . '%';
            case self::OPERATOR_CONTAINS:
                return DatabaseSearching::getFullTextLikeSearchString($value);
            case self::OPERATOR_IS:
                return DatabaseSearching::getLikeSearchString($value);
        }
    }


    /**
     * @param string $operator
     * @return string
     */
    protected function getDqlOperator($operator)
    {
        switch ($operator) {
            case self::OPERATOR_AFTER:
            case self::OPERATOR_CONTAINS:
                return 'LIKE';
            case self::OPERATOR_IS:
                return '=';
        }
    }

}