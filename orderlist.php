<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Orderlist extends Module implements WidgetInterface
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'orderlist';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Inform-All';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Order List');
        $this->description = $this->l('Customers can make a list of their favorite products and re-order them.');

        $this->confirmUninstall = $this->l(
            'Are you sure you want to uninstall and delete all the existing order lists?'
        );

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('ORDERLIST_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayCustomerAccount') &&
            $this->registerHook('displayProductAdditionalInfo');
    }

    public function uninstall()
    {
        Configuration::deleteByName('ORDERLIST_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');

    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

	public function getWidgetVariables($hookName, array $params)
	{
		return array(
			'my_list' => Context::getContext()->link->getModuleLink($this->name, 'my_list'),
		);
	}

	public function renderWidget($hookName, array $params)
	{
		$this->smarty->assign($this->getWidgetVariables($hookName, $params));
		return $this->display(dirname(__FILE__), '/views/templates/hook/go_to_orderlist_widget.tpl');
	}

    public function hookDisplayCustomerAccount()
    {
	    $this->smarty->assign($this->getWidgetVariables('displayCustomerAccount', array()));
        return $this->display(dirname(__FILE__), '/views/templates/hook/go_to_orderlist.tpl');
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $product = $params['product'];
        $this->context->smarty->assign($this->name, Configuration::get('orderlist'));

        $this->context->smarty->assign(
            'add_product_link',
            Context::getContext()->link->getModuleLink(
                $this->name,
                'edit_list_product',
                array('product_id' => (int)$product->getId())
            )
        );

        if ($this->context->customer->id) {
            $this->context->smarty->assign(
                'alreadyInAList',
                $this->alreadyInList($product->getId(), $this->context->customer->id)
            );

            return $this->display(__FILE__, 'views/templates/hook/add_to_orderlist.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/hook/login_for_orderlist.tpl');
        }

    }

    public function alreadyInList($product_id, $customer_id)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('orderlist', 'ol');
        $sql->where('ol.id_customer = '.(int)$customer_id);
        $sql->where('ol.id_product = '.(int)$product_id);

        return empty(Db::getInstance()->executeS($sql));
    }
}
