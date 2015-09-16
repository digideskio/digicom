<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined ('_JEXEC') or die ("Go away.");
$app		= JFactory::getApplication();
$input	= $app->input;
$tab		= $input->get('tab','sales');
$input->set('layout', 'dgtabs');
JFactory::getDocument()->addScript( JURI::root(true)."/media/com_digicom/js/chart.min.js");

?>
<div id="digicom_reports" class="digicom">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

		<ul class="nav nav-tabs">
			<li<?php echo ($tab == 'sales' ? ' class="active"' : '');?>>
				<a href="<?php echo JRoute::_('index.php?option=com_digicom&view=reports&tab=sales&report=sales_by_date&range=7day');?>">Sales</a>
			</li>
			<!--
			<li<?php echo ($tab == 'customers' ? ' class="active"' : '');?>>
				<a href="<?php echo JRoute::_('index.php?option=com_digicom&view=reports&tab=customers&report=customers_new&range=7day');?>">Customers</a>
			</li>
			<li<?php echo ($tab == 'downloads' ? ' class="active"' : '');?>>
				<a href="<?php echo JRoute::_('index.php?option=com_digicom&view=reports&tab=downloads&report=downloads_top&range=7day');?>">Downloads</a>
			</li>
			-->
		</ul>
		
		<p class="clearfix"></p>

		<section class="reportsWrapper">
			<?php echo $this->loadTemplate($tab); ?>
		</section>

		<input type="hidden" name="view" value="reports" />
		<input type="hidden" name="option" value="com_digicom" />
		<input type="hidden" name="task" value="showStats" />
	</div>
</div>


<div class="dg-footer">
	<?php echo JText::_('COM_DIGICOM_CREDITS'); ?>
</div>
