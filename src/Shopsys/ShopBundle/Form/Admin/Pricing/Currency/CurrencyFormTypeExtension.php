<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Form\Admin\Pricing\Currency;

use PHPUnit\Framework\Constraint\GreaterThan;
use Shopsys\FrameworkBundle\Form\Admin\Pricing\Currency\CurrencyFormType as CurrencyFormTypeFramework;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CurrencyFormTypeExtension extends AbstractTypeExtension
{




    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->setupExchangeBuilder($builder, $options);

    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    protected function setupExchangeBuilder(FormBuilderInterface $builder, array $options)
    {


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

    public function getExtendedType(){
        return CurrencyFormTypeFramework::class;
    }
}
