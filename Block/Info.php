<?php
namespace Paypay\Multibanco\Block;


use Magento\Framework\Phrase;
use Magento\Framework\Registry;


class Info extends \Magento\Payment\Block\Info
{

    public function getSpecificInformation()
    {
        $informations['Entidade'] = $this->getInfo()->getAdditionalInformation('entidade');
        $informations['Referencia'] = $this->getInfo()->getAdditionalInformation('referencia');
        return (object)$informations;
    }

    public function getMethodCode()
    {
        return $this->getInfo()->getMethodInstance()->getCode();
    }

}
