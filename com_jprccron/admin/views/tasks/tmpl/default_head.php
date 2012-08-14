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
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
    <th width="5">
        <?php echo JText::_('COM_JPRCCRON_JPRCCRON_HEADING_ID'); ?>
    </th>
    <th width="20">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
    </th>			
    <th>
        <?php echo JText::_('COM_JPRCCRON_JPRCCRON_HEADING_TASK'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_JPRCCRON_JPRCCRON_HEADING_PUBLISHED'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_JPRCCRON_JPRCCRON_HEADING_MHDMD'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_JPRCCRON_JPRCCRON_HEADING_RAN_AT'); ?>
    </th>    
</tr>
