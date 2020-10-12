<?php

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class orderlistmy_listModuleFrontController extends ModuleFrontController
{
    /**
     * @throws PrestaShopException
     */
    public function initContent()
    {
        if (empty($this->context->customer->id)) {
            Tools::redirect('index.php');
        }
        parent::initContent();
        $productsIDS = $this->myList($this->context->customer->id);
        $products = $this->assembleProducts($productsIDS);
        $removelinks = $this->deletionLinks($productsIDS);

        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('removelinks', $removelinks);

        $this->setTemplate('module:orderlist/views/templates/front/my_orderlist.tpl');
    }

    public function myList($customer_id)
    {
        $sql = new DbQuery();
        $sql->select('id_product');
        $sql->from('orderlist', 'ol');
        $sql->where('ol.id_customer = '.(int)$customer_id);
        $sql->orderBy('id_product');

        #return Db::getInstance()->executeS($sql);
        return Db::getInstance()->executeS($sql);
    }

    public function assembleProducts($orderListProductIDS)
    {
        // Product handling - to get relevant data
        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $products = array();
        foreach ($orderListProductIDS as $rawProduct) {
            $product = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
            array_push($products, $product);

        }

        return $products;
    }

    public function deletionLinks($productIDS)
    {
        $removelinks = array();
        foreach ($productIDS as $prodID) {
            $removelinks[$prodID['id_product']] = $this->context->link->getModuleLink(
                'orderlist',
                'edit_list_product',
                array('product_id' => $prodID['id_product'], 'delete_list_product' => true)
            );
        }

        return $removelinks;


    }

}