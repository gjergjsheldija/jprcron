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

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jprccron&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="jprccron-form" class="form-validate">

    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JPRCCRON_TASK_DETAILS'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('details') as $field): ?>
                    <li><?php
                echo $field->label;
                echo $field->input;
                    ?></li>
                <?php endforeach; ?>
            </ul>
    </div>

    <div class="width-40 fltrt">
        <?php echo JHtml::_('sliders.start', 'jprccron-slider'); ?>


        <?php echo JHtml::_('sliders.panel', JText::_('COM_JPRCCRON_TASK_FIELD_EXECUTION_LABEL'), 'execution-params'); ?>
        <fieldset class="panelform" >
            <dl class="adminformlist">
                <dt><?php echo JText::_('COM_JPRCCRON_TASK_FIELD_RUNAT_LABEL'); ?></dt>
                <dd><?php echo $this->item->ran_at; ?></dd>
                <dt><?php echo JText::_('COM_JPRCCRON_TASK_FIELD_LAST_RUN_LABEL'); ?></dt>
                <dd><?php echo $this->item->last_run; ?></dd>
                <dt><?php echo JText::_('COM_JPRCCRON_TASK_FIELD_LAST_LOG_LABEL'); ?></dt>
                <dd><?php echo $this->item->log_text; ?></dd>
            </dl>            
        </fieldset>
        <?php echo JHtml::_('sliders.end'); ?>
    </div>

    <div>
        <input type="hidden" name="task" value="jprccron.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
