<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;
use Joomla\Registry\Registry;
use Joomla\String\String;
/**
 * Methods supporting a list of product records.
 *
 * @since  1.6
 */
class DigiComModelProducts extends JModelList
{
	protected $_context = 'com_digicom.product';
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JControllerLegacy
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'alias', 'a.alias',
				'price,a.price',
				'product_type,a.product_type',
				'bundle_source,a.bundle_source',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'hits', 'a.hits',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
			);
			if (JLanguageAssociations::isEnabled())
			{
				$config['filter_fields'][] = 'association';
			}
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$product_type = $this->getUserStateFromRequest($this->context . '.filter.product_type', 'filter_product_type');
		$this->setState('filter.product_type', $product_type);

		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
		$this->setState('filter.level', $level);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$tag = $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', '');
		$this->setState('filter.tag', $tag);

		// List state information.
		parent::populateState('a.id', 'desc');

		// Force a language
		$forcedLanguage = $app->input->get('forcedLanguage');

		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}

		$this->setState('list.start', $app->getUserStateFromRequest($this->_context . '.list.start', 'limitstart', 0, 'int'));
		$this->setState('list.limit', $app->getUserStateFromRequest($this->_context . '.list.limit', 'limit', $app->getCfg('list_limit', 25) , 'int'));

	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.product_type');
		$id .= ':' . $this->getState('filter.author_id');
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.name, a.alias,a.price,a.images, a.product_type, a.checked_out, a.checked_out_time, a.catid, a.created_by, a.hide_public, a.featured,' .
				'a.price_type,a.expiration_length, a.expiration_type,' .
				'a.hits, a.published, a.access, a.ordering, a.language, a.publish_up, a.publish_down'
			)
		);
		$query->from($db->quoteName('#__digicom_products') . ' AS a');

		// Join over the language
		$query->select('l.title AS language_title')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category_title')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Join over the associations.
		if (JLanguageAssociations::isEnabled())
		{
			$query->select('COUNT(asso2.id)>1 as association')
				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_digicom.item'))
				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
				->group('a.id, l.title, uc.name, ag.title, c.title, ua.name');
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by published state
		$product_type = $this->getState('filter.product_type');

		if (!empty($product_type))
		{
			$query->where('a.product_type = "' . $product_type .'"');
		}

		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN (' . $categoryId . ')');
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('c.level <= ' . ((int) $level + (int) $baselevel - 1));
		}

		// Filter by author
		$authorId = $this->getState('filter.author_id');

		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int) $authorId);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(a.name LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}

		// Filter by a single tag.
		$tagId = $this->getState('filter.tag');

		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_digicom.product')
				);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		if ($orderCol == 'a.ordering' || $orderCol == 'category_title')
		{
			$orderCol = 'c.title ' . $orderDirn . ', a.ordering';
		}

		// SQL server change
		if ($orderCol == 'language')
		{
			$orderCol = 'l.title';
		}

		if ($orderCol == 'access_level')
		{
			$orderCol = 'ag.title';
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
	/**
	 * Build a list of authors
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__digicom_products AS c ON c.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}

	/**
	 * Method to get a list of articles.
	 * Overridden to add a check for access levels.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		if ($items = parent::getItems())
		{
			foreach($items as $key=>$item)
			{
					// Convert the images field to an array.
					$registry = new Registry;
					$registry->loadString($item->images);
					$item->images = $registry->toArray();
					if(isset($item->images['image_intro'])){
						$item->image_intro	=	$item->images['image_intro'];
						$item->image_full		=	$item->images['image_full'];
					}else{
						$item->image_intro	=	$item->images;
					}
			}
		}

		// if (JFactory::getApplication()->isSite())
		// {
		// 	$user = JFactory::getUser();
		// 	$groups = $user->getAuthorisedViewLevels();
		//
		// 	for ($x = 0, $count = count($items); $x < $count; $x++)
		// 	{
		// 		// Check the access level. Remove articles the user shouldn't see
		// 		if (!in_array($items[$x]->access, $groups))
		// 		{
		// 			unset($items[$x]);
		// 		}
		// 	}
		// }

		return $items;
	}
	function getConfigs() {
		$comInfo = JComponentHelper::getComponent('com_digicom');
		return $comInfo->params;
	}

}
