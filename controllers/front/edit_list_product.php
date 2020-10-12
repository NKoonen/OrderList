<?php

class orderlistedit_list_productModuleFrontController extends ModuleFrontController
{

    /**
     * @throws PrestaShopException
     */
    public function initContent()
    {
        $customer = $this->context->customer;
        if (!$this->context->customer->isLogged()) {
            die();
        }

        if (Tools::getValue('product_id') !== null) {
            $product_id = Tools::getValue('product_id');
        } else {
            die();
        }

        /**
         * @var \Db $db
         */
        $db = \Db::getInstance();

        if (Tools::getValue('delete_list_product')) {
            /** @var bool $result */
            $result = $db->delete(
                    'orderlist',
                    'id_product = '.(int)$product_id
                ).' AND WHERE id_customer '.(int)$customer->id;
            Tools::redirect(Context::getContext()->link->getModuleLink('orderlist', 'my_list'));

        } else {
            /** @var bool $result */
            $result = $db->insert(
                'orderlist',
                array(
                    'id_customer' => (int)$customer->id,
                    'id_product' => (int)$product_id,
                )
            );
            Tools::redirect($this->context->link->getProductLink($product_id));
        }


    }

}