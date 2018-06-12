<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Quickicon.privacycheck
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Plugin to check the PHP version and display a warning about its support status
 *
 * @since  3.9.0
 */
class PlgQuickiconPrivacyCheck extends JPlugin
{
	/**
	 * Constant representing the active PHP version being fully supported
	 *
	 * @var    integer
	 * @since  3.7.0
	 */
	const PHP_SUPPORTED = 0;

	/**
	 * Constant representing the active PHP version receiving security support only
	 *
	 * @var    integer
	 * @since  3.7.0
	 */
	const PHP_SECURITY_ONLY = 1;

	/**
	 * Constant representing the active PHP version being unsupported
	 *
	 * @var    integer
	 * @since  3.7.0
	 */
	const PHP_UNSUPPORTED = 2;

	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 * @since  3.7.0
	 */
	protected $app;
	protected $db;

	/**
	 * Load plugin language files automatically
	 *
	 * @var    boolean
	 * @since  3.7.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Check the PHP version after the admin component has been dispatched.
	 *
	 * @param   string  $context  The calling context
	 *
	 * @return  void
	 *
	 * @since   3.9.0
	 */
	public function onGetIcons($context)
	{
		if ($context !== $this->params->get('context', 'mod_quickicon') || !JFactory::getUser()->authorise('core.manage', 'com_privacy'))
		{
			return;
		}

		JHtml::_('jquery.framework');

		$token    = JSession::getFormToken() . '=' . 1;
		$url      = JUri::base() . 'index.php?option=com_privacy&' . $token;
		$ajax_url = JUri::base() . 'index.php?option=com_privacy&task=ajax&' . $token;
		$script   = array();
		$script[] = 'var plg_quickicon_privacycheck_url = \'' . $url . '\';';
		$script[] = 'var plg_quickicon_privacycheck_ajax_url = \'' . $ajax_url . '\';';
		$script[] = 'var plg_quickicon_privacycheck_text = {'
			. '"UPTODATE" : "' . JText::_('PLG_QUICKICON_PRIVACYCHECK_UPTODATE', true) . '",'
			. '"UPDATEFOUND": "' . JText::_('PLG_QUICKICON_PRIVACYCHECK_UPDATEFOUND', true) . '",'
			. '"UPDATEFOUND_MESSAGE": "' . JText::_('PLG_QUICKICON_PRIVACYCHECK_UPDATEFOUND_MESSAGE', true) . '",'
			. '"UPDATEFOUND_BUTTON": "' . JText::_('PLG_QUICKICON_PRIVACYCHECK_UPDATEFOUND_BUTTON', true) . '",'
			. '"ERROR": "' . JText::_('PLG_QUICKICON_PRIVACYCHECK_ERROR', true) . '",'
			. '};';
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		JHtml::_('script', 'plg_quickicon_privacycheck/privacycheck.js', array('version' => 'auto', 'relative' => true));

		return array(
			array(
				'link'  => 'index.php?option=com_privacy&view=requests&' . $token,
				'image' => 'asterisk',
				'icon'  => 'header/icon-48-user.png',
				'text'  => JText::_('PLG_QUICKICON_PRIVACYCHECK_CHECKING'),
				'id'    => 'plg_quickicon_privacycheck',
				'group' => 'MOD_QUICKICON_MAINTENANCE'
			)
		);
	}
}
