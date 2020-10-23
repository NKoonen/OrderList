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
	        $result = $this->deleteListProduct( $product_id, $customer );
		        Tools::redirect(Context::getContext()->link->getModuleLink('orderlist', 'my_list'));

        } else {
            /** @var bool $result */
            $result = $this->addListProduct( $product_id, $customer );
	            Tools::redirect($this->context->link->getProductLink($product_id));
        }


    }

    public function deleteListProduct( $product_id, $customer ) {
	    $db = \Db::getInstance();

	    if ( ! is_numeric( $customer ) ) {
		    $customer = $customer->id;
	    }

	    /** @var bool $result */
	    return $db->delete(
		    'orderlist',
		    'id_product = ' . (int) $product_id . ' AND id_customer = ' . (int) $customer
	    );
    }

	public function addListProduct( $product_id, $customer ) {
		$db = \Db::getInstance();

		if ( ! is_numeric( $customer ) ) {
			$customer = $customer->id;
		}

		return $db->insert(
			'orderlist',
			array(
				'id_customer' => (int) $customer,
				'id_product'  => (int) $product_id,
			)
		);
	}

}