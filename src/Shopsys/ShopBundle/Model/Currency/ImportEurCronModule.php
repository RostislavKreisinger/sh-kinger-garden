<?php

declare(strict_types=1);


namespace Shopsys\ShopBundle\Model\Currency;


use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyData;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\Plugin\Cron\SimpleCronModuleInterface;
use Symfony\Bridge\Monolog\Logger;

class ImportEurCronModule implements SimpleCronModuleInterface
{
    public const DATE_PLACEHOLDER = '[currentDate]';//10.09.2018
    public const CURRENCY_CNB_URL = 'http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt?date='.self::DATE_PLACEHOLDER;

    /**
     * @var CurrencyFacade
     */
    protected $currencyFacade;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ImportEurCronModule constructor.
     * @param CurrencyFacade $currencyFacade
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CurrencyFacade $currencyFacade, EntityManagerInterface $entityManager){
        $this->currencyFacade = $currencyFacade;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger){

        // TODO: Implement setLogger() method.
    }

    public function run(){

        foreach ($this->loadRows() as $row){
            if(strpos($row, 'EMU|') !== false){

                $importedCurrencyRate = explode('|', $row);

                $currencyData = $this->buildCurrencyData($importedCurrencyRate);
                if($currencyData instanceof CurrencyData){

                    $this->saveNewCurrencyRate(
                        $currencyData,
                        $this->getEurCurrencyId()
                    );

                }
            }
        }
    }

    /**
     * @return int|null
     */
    protected function getEurCurrencyId():?int{
        foreach ($this->currencyFacade->getAll() as $currency){
            if($currency->getCode() === Currency::CODE_EUR){
                return $currency->getId();
            }
        }
        return null;
    }

    /**
     * @param array $importedCurrencyRate
     * @return CurrencyData|null
     */
    protected function buildCurrencyData(array $importedCurrencyRate):?CurrencyData{

        if(empty($importedCurrencyRate[1]) || empty($importedCurrencyRate[3] || empty($importedCurrencyRate[4]))){
            //log
            return null;
        }

        $currencyData = new CurrencyData();
        $currencyData->name = $importedCurrencyRate[1];
        $currencyData->code = $importedCurrencyRate[3];
        $currencyData->exchangeRate = $importedCurrencyRate[4];

        return $currencyData;
    }

    /**
     * @param CurrencyData $currencyData
     * @param int|null $eurCurrencyId
     */
    protected function saveNewCurrencyRate(CurrencyData $currencyData, int $eurCurrencyId = null){
        $this->entityManager->beginTransaction();
        if($eurCurrencyId === null){
            $this->currencyFacade->create($currencyData);
        }else{
            $this->currencyFacade->edit($eurCurrencyId, $currencyData);
        }
        $this->entityManager->commit();
    }

    /**
     * @return array
     */
    protected function loadRows():array {
        $date = date('d.m.Y');
        $url = str_replace(self::DATE_PLACEHOLDER, $date, self::CURRENCY_CNB_URL);
        $result = file_get_contents($url);
        return explode('\n', $result);
    }


}