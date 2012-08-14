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
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jprccron')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// require helper file
JLoader::register('JPrcCronHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'jprccron.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by JPrcCron
$controller = JController::getInstance('JPrcCron');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
