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

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of JPrcCron component
 */
class JPrcCronController extends JController {

    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false) {
        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'Tasks'));

        // call parent behavior
        parent::display($cachable);

        // Set the submenu
        JPrcCronHelper::addSubmenu('tasks');
    }

}
