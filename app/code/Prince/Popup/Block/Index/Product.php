<?php


namespace Prince\Popup\Block\Index;

class Product extends \Magento\Framework\View\Element\Template
{

    protected $_helper;

    protected $templateProcessor;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Prince\Popup\Helper\Data $helper,
        \Zend_Filter_Interface $templateProcessor
    )
    {
        $this->_helper = $helper;
        $this->templateProcessor = $templateProcessor;
        parent::__construct($context);
    }

    public function getCollection()
    {   
        $sku = $this->_helper->getCurrentProductSku();
        $collection = $this->_helper->getCollection();
        $popupCollection = $collection->addFieldToFilter('display', array('finset' => '2'));
        $popupCollection = $collection->addFieldToFilter('product', array('finset' => $sku));
        $popupCollection = $collection->addFieldToFilter(
            'customer_group',
                array(
                    array('null' => true),
                    array('finset' => $this->_helper->getCurrentCustomer())
                    )
                );
        $popupCollection = $collection->addFieldToFilter(
            'storeview', 
            array(
                array('eq' => 0),
                array('finset' => $this->_helper->getCurrentStore())
                )
            );
        
        return $popupCollection;
    }

    public function filterOutputHtml($string) 
    {
        return $this->templateProcessor->filter($string);

    }

}

