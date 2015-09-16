<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;
?>
<?php
$db = JFactory::getDBO();
$sql = "select `title`, `alias`, `catid`, `introtext` from #__content where id=".intval($this->configs->get('termsid'));
$db->setQuery($sql);
$db->query();
$result = $db->loadAssocList();
$terms_title = $result["0"]["title"];
$terms_content = $result["0"]["introtext"];
$layoutData = array(
  'selector' => 'paymentAlertModal',
  'params'   => array(
                  'title' => JText::_("COM_DIGICOM_WARNING"),
                  'height' => '400px',
                  'width' => '1280',
                  'footer' => '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>'
                ),
  'body'     => JText::_("COM_DIGICOM_CART_PAYMENT_METHOD_REQUIRED_NOTICE")
);
echo JLayoutHelper::render('bt3.modal.main', $layoutData);

if($this->configs->get('askterms',0) == '1' && ($this->configs->get('termsid',0) > 0)):
  $layoutData = array(
    'selector' => 'termsAlertModal',
    'params'   => array(
                    'title' => JText::_("COM_DIGICOM_WARNING"),
                    'height' => '400px',
                    'width' => '1280',
                    'footer' => '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>'
                  ),
    'body'     => JText::_("COM_DIGICOM_CART_ACCEPT_TERMS_CONDITIONS_REQUIRED_NOTICE")
  );
  echo JLayoutHelper::render('bt3.modal.main', $layoutData);

  $layoutData = array(
    'selector' => 'termsShowModal',
    'params'   => array(
                    'title' => $terms_title,
                    'height' => 'auto',
                    'width' => '1280',
                    'footer' => '<button data-digicom-id="action-agree" class="action-agree btn btn-success" data-dismiss="modal" aria-hidden="true">' . JText::_("COM_DIGICOM_CART_AGREE_TERMS_BUTTON") . '</button> <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>'

                  ),
    'body'     => $terms_content
  );
  echo JLayoutHelper::render('bt3.modal.main', $layoutData);
endif;
?>
