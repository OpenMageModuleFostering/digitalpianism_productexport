<?php
/**
 * Class DigitalPianism_ProductExport_Adminhtml_IndexController
 */
class DigitalPianism_ProductExport_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function massExportAction()
    {
        $productIds = $this->getRequest()->getParam('product');
        if (!is_array($productIds)) {
            $this->_getSession()->addError($this->__('Please select product(s).'));
            $this->_redirect('adminhtml/catalog_product/index');
        }
        else {
            //write headers to the csv file
            $content = "id,name,url,sku,price,special_price\n";
            try {
			
				$collection = Mage::getResourceModel('catalog/product_collection')
					->addFieldToFilter('entity_id', array($productIds))
					->addAttributeToSelect(array('entity_id','name','product_url','sku','price','special_price'));
				
                foreach ($collection as $product) {
                    $content .= "\"{$product->getId()}\",\"{$product->getName()}\",\"{$product->getProductUrl()}\",\"{$product->getSku()}\",\"{$product->getPrice()}\",\"{$product->getSpecialPrice()}\"\n";
                }
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('adminhtml/catalog_product/index');
            }
            $this->_prepareDownloadResponse('export.csv', $content, 'text/csv');
        }

    }
}