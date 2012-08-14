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
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td>
            <?php echo $item->id; ?>
        </td>
        <td>
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo JRoute::_('index.php?option=com_jprccron&task=task.edit&id='.(int) $item->id); ?>"><?php echo $item->task; ?></a>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'contacts.', false); ?>
        </td>
        <td>
            <?php echo $item->unix_mhdmd; ?>
        </td>
        <td>
            <?php echo $item->ran_at; ?>
        </td>

    </tr>
<?php endforeach; ?>
