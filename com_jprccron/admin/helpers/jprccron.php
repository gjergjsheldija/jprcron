<?php

/* ------------------------------------------------------------------------
  # jprccron - Joomla cronjobs component for J2.5
  # ------------------------------------------------------------------------
  # author    Prieco S.A.
  # copyright Copyright (C) 2012 Prieco.com. All Rights Reserved.
  # @license - http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  # Websites: http://www.prieco.com
  # Technical Support:  Forum - http://www.prieco.com/en/forum/index.html
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * JPrcCron component helper.
 */
abstract class JPrcCronHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu) {
        JSubMenuHelper::addEntry(JText::_('COM_JPRCCRON_SUBMENU_TASKS'), 'index.php?option=com_jprccron', $submenu == 'tasks');
	JSubMenuHelper::addEntry(JText::_('COM_JPRCCRON_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_jprccron', $submenu == 'categories');        

        // set some global property
        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-48-jprccron {background-image: url(../media/com_jprccron/images/prieco-48x48.png);}');
        if ($submenu == 'categories')
            $document->setTitle(JText::_('COM_JPRCCRON_ADMINISTRATION_CATEGORIES'));
    }

    /**
     * Get the actions
     */
    public static function getActions($jobId = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($jobId)) {
            $assetName = 'com_jprccron';
        } else {
            $assetName = 'com_jprccron.job.' . (int) $jobId;
        }

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
        );

        foreach ($actions as $action)
            $result->set($action, $user->authorise($action, $assetName));

        return $result;
    }

}
