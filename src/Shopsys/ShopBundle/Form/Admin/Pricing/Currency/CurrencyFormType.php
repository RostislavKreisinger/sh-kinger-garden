<?php

declare(strict_types=1);


namespace Shopsys\ShopBundle\Form\Admin\Pricing\Currency;

use CommerceGuys\Intl\Currency\CurrencyRepositoryInterface;
use PHPUnit\Framework\Constraint\GreaterThan;
use Shopsys\FrameworkBundle\Form\Admin\Pricing\Currency\CurrencyFormType as CurrencyFormTypeFramework;
use Shopsys\FrameworkBundle\Model\Localization\Localization;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CurrencyFormType extends CurrencyFormTypeFramework
{

    /**
     * CurrencyFormType constructor.
     * @param CurrencyRepositoryInterface $intlCurrencyRepository
     * @param Localization $localization
     */
    public function __construct(CurrencyRepositoryInterface $intlCurrencyRepository, Localization $localization){
        parent::__construct($intlCurrencyRepository, $localization);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

        parent::buildForm($builder, $options);

        $this->setupExchangeBuilder($builder, $options);
    }

    protected function setupExchangeBuilder(FormBuilderInterface $builder, array $options){

        $builder->add('exchangeRate', NumberType::class, [
            'disabled' => true,
            'scale' => 6,
            'attr' => [
                'readonly' => $options['is_default_currency'],
            ],
            'constraints' => [
                new NotBlank(['message' => 'Please enter currency exchange rate']),
                new GreaterThan(0),
            ],
        ]);

    }

}