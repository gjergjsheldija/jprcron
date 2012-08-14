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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Task View
 */
class JPrcCronViewTask extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;

        // Set the toolbar
        $this->addToolBar();

        /* Set drop-down list with tasks  */
        $option = array();
        $option[] = JHTML::_('select.option', '0', 'SSH Command');
        $option[] = JHTML::_('select.option', '1', 'Web Address fopen()');
        $option[] = JHTML::_('select.option', '2', 'Web Address fsockopen()');
        $option[] = JHTML::_('select.option', '3', 'Plugin');  //Added #PN 2010-02-15
        $crontype = JHTML::_('select.genericlist', $option, 'jform_type', null, 'value', 'text', $cron->type);
        $cron->type = $crontype;

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $userId = $user->id;
        $isNew = $this->item->id == 0;
        $canDo = JPrcCronHelper::getActions($this->item->id);
        JToolBarHelper::title($isNew ? JText::_('COM_JPRCCRON_MANAGER_JPRCCRON_NEW') : JText::_('COM_JPRCCRON_MANAGER_JPRCCRON_EDIT'), 'jprccron');
        // Built the actions for new and existing records.
        if ($isNew) {
            // For new records, check the create permission.
            if ($canDo->get('core.create')) {
                JToolBarHelper::apply('task.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('task.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom('task.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            JToolBarHelper::cancel('task.cancel', 'JTOOLBAR_CANCEL');
        } else {
            if ($canDo->get('core.edit')) {
                // We can save the new record
                JToolBarHelper::apply('task.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('task.save', 'JTOOLBAR_SAVE');

                // We can save this record, but check the create permission to see if we can return to make a new one.
                if ($canDo->get('core.create')) {
                    JToolBarHelper::custom('task.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
                }
            }
            if ($canDo->get('core.create')) {
                JToolBarHelper::custom('task.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel('task.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_JPRCCRON_TASK_CREATING') : JText::_('COM_JPRCCRON_TASK_EDITING'));
        $document->addStyleSheet('components/com_jprccron/assets/style.css');
        $document->addScript('components/com_jprccron/assets/script.js');
        JText::script('COM_JPRCCRON_TASK_ERROR_UNACCEPTABLE');
    }

    function dayToString($day, $abbr = false) {
        switch ($day) {
            case 0: return $abbr ? JText::_('SUN') : JText::_('SUNDAY');
            case 1: return $abbr ? JText::_('MON') : JText::_('MONDAY');
            case 2: return $abbr ? JText::_('TUE') : JText::_('TUESDAY');
            case 3: return $abbr ? JText::_('WED') : JText::_('WEDNESDAY');
            case 4: return $abbr ? JText::_('THU') : JText::_('THURSDAY');
            case 5: return $abbr ? JText::_('FRI') : JText::_('FRIDAY');
            case 6: return $abbr ? JText::_('SAT') : JText::_('SATURDAY');
        }
    }

    function monthToString($month, $abbr = false) {
        switch ($month) {
            case 1: return $abbr ? JText::_('JANUARY_SHORT') : JText::_('JANUARY');
            case 2: return $abbr ? JText::_('FEBRUARY_SHORT') : JText::_('FEBRUARY');
            case 3: return $abbr ? JText::_('MARCH_SHORT') : JText::_('MARCH');
            case 4: return $abbr ? JText::_('APRIL_SHORT') : JText::_('APRIL');
            case 5: return $abbr ? JText::_('MAY_SHORT') : JText::_('MAY');
            case 6: return $abbr ? JText::_('JUNE_SHORT') : JText::_('JUNE');
            case 7: return $abbr ? JText::_('JULY_SHORT') : JText::_('JULY');
            case 8: return $abbr ? JText::_('AUGUST_SHORT') : JText::_('AUGUST');
            case 9: return $abbr ? JText::_('SEPTEMBER_SHORT') : JText::_('SEPTEMBER');
            case 10: return $abbr ? JText::_('OCTOBER_SHORT') : JText::_('OCTOBER');
            case 11: return $abbr ? JText::_('NOVEMBER_SHORT') : JText::_('NOVEMBER');
            case 12: return $abbr ? JText::_('DECEMBER_SHORT') : JText::_('DECEMBER');
        }
    }

}
