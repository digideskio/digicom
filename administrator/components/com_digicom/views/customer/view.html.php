<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;

class DigiComViewCustomer extends JViewLegacy {

	function display ($tpl =  null )
	{
		//require_once( JPATH_COMPONENT.DS.'helpers'.DS.'sajax.php' );
		$db = JFactory::getDBO();
		$customer = $this->get('customer');
		//print_r($customer);die;
		$user = $this->get('User');
		$isNew = ($customer->id < 1);
		$text = $isNew?JText::_('COM_DIGICOM_NEW'):JText::_('COM_DIGICOM_EDIT')." : ".$customer->name;

		JToolBarHelper::title(JText::_('COM_DIGICOM_CUSTOMER').":<small>[".$text."]</small>");

		$bar = JToolBar::getInstance('toolbar');
		$layout = new JLayoutFile('toolbar.title');
		$title=array(
			'title' => JText::_('COM_DIGICOM_CUSTOMER').":<small>[".$text."]</small>",
			'class' => 'title'
		);
		$bar->appendButton('Custom', $layout->render($title), 'title');

		$layout = new JLayoutFile('toolbar.settings');
		$bar->appendButton('Custom', $layout->render(array()), 'settings');

		JToolBarHelper::apply('customer.apply');
		JToolBarHelper::save('customer.save');

		JToolBarHelper::divider();
		JToolBarHelper::cancel ('customer.cancel');


		$this->assign("cust", $customer);
		$this->assign("user", $user);

		$configs = $this->get("Configs");

		$this->assign("configs", $configs);

		DigiComHelperDigiCom::addSubmenu('customers');
		$this->sidebar = DigiComHelperDigiCom::renderSidebar();

		parent::display($tpl);
	}





}
