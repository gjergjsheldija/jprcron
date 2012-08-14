<?php

/* ------------------------------------------------------------------------
  # jprcron - Joomla cronjobs plugin for J1.6/1.7
  # ------------------------------------------------------------------------
  # author    Prieco S.A.
  # copyright Copyright (C) 2012 Prieco.com. All Rights Reserved.
  # @license - http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  # Websites: http://www.prieco.com
  # Technical Support:  Forum - http://www.prieco.com/en/contact.html
  ------------------------------------------------------------------------- */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

class plgSystemJPrCron extends JPlugin {

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param	object		$subject The object to observe
     * @param 	array  		$config  An array that holds the plugin configuration
     * @since	1.0
     */
    function plgSystemJPrCron(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    function onAfterRender() {
        $component = JComponentHelper::getComponent('com_jprccron');
        $params = new JParameter($component->params);
        $app = &JFactory::getApplication();
        if ($params->get('cronenable'))
            if ($app->isSite()) {
                require_once( JPATH_PLUGINS . "/system/jprcron/jprcron/CronParser.class.php");
                ########## GET CONFIG VARIABLES ##########################
                $_CONFIG['cemail'] = $params->get('cemail');
                $_CONFIG['email'] = $params->get('email');
                #########################################################

                $database = JFactory::getDBO();

                $query = "SELECT* from #__jprccron_tasks WHERE published=1";
                $database->setQuery($query);
                $data = $database->loadObjectList();

                $cron = new CronParser();

                foreach ($data as $value) {

                    if (($value->last_run == false) && ($_CONFIG['cemail']) && ($_CONFIG['email'])) {
                        $mailer = & JFactory::getMailer();
                        $config = & JFactory::getConfig();
                        $sender = array(
                            $config->getValue('config.mailfrom'),
                            $config->getValue('config.fromname'));
                        $mailer->setSender($sender);
                        $mailer->addRecipient($_CONFIG['email']);

                        $body = "A cron task was failed, " . ($value->task) . " crashed when we tried to run it.";
                        $mailer->setSubject('JPrCron - Alert');
                        $mailer->setBody($body);
                        $send = & $mailer->Send();
                    }

                    if ($cron->calcLastRan($value->unix_mhdmd)) {
                        $lastRan = $cron->getLastRan(); //Array (0=minute, 1=hour, 2=dayOfMonth, 3=month, 4=week, 5=year)
                        //Convert to Unix timestamp
                        $cron_ran = mktime($lastRan[1], $lastRan[0], 0, $lastRan[3], $lastRan[2], $lastRan[5]);
                        $now_date = date('Y-m-d H:i:s');
                        if (date('Y-m-d H:i:s', $cron->getLastRanUnix()) > $value->ran_at) {
                            $query = "UPDATE #__jprccron_tasks SET ran_at = '" . $now_date . "', last_run = 0 WHERE id = '" . ($value->id) . "'";
                            $database->setQuery($query);
                            $database->query();

                            $log = '';
                            $command = $value->file;
                            if ($value->type == 0) {
                                exec($command, $log);
                                $log .= "\n## exec(" . $command . ") ##\n";
                            } else if ($value->type == 1) {
                                if ($fp = fopen($command, 'r')) {
                                    while (!feof($fp))
                                        $log .= fread($fp, 1024);
                                    fclose($fp);
                                    $log .= "\n## fopen(" . $command . ") ##\n";
                                } else {
                                    $log = "Unable to open " . $command;
                                }
                            }
                            //Was "else"  #PN 2010-02-15
                            else if ($value->type == 2) {
                                $urlpart = parse_url($command);
                                $port = (isset($urlpart['port']) ? $urlpart['port'] : "80");
                                $fp = fsockopen($urlpart['host'], $port, $errno, $errstr, 30);
                                if (!$fp) {
                                    $log = "Error (" . $errstr . ") - (" . $errno . ")<br />\n";
                                } else {
                                    $out = "GET " . $urlpart['path'] . (isset($urlpart['query']) ? '?' . $urlpart['query'] : '');
                                    $out .= " HTTP/1.1\r\n";
                                    $out .= "Host: " . $urlpart['host'] . "\r\n";
                                    $out .= "Connection: Close\r\n\r\n";
                                    $log = "OUT is(" . $out . ")";
                                    fwrite($fp, $out);
                                    while (!feof($fp)) {
                                        $log .=fgets($fp, 128);
                                    }
                                    fclose($fp);
                                }
                            }
                            //Section added #PN 2010-02-15
                            //If it's a plugin...
                            else {
                                //Construct asn array from the task descriptor:

                                $aryEvent = explode('.', $value->file);

                                //If it's not an array...
                                if (!is_array($aryEvent)) {
                                    //Skip it.
                                }
                                //If there are two elements in the array...
                                elseif (count($aryEvent) == 3) {
                                    //The elements are the plugin group and event names respectively:
                                    $pluginGroup    = $aryEvent[0];
                                    $pluginName     = $aryEvent[1];
                                    $pluginEvent    = $aryEvent[2];

                                    //Prepare the dispatcher object:
                                    $dispatcher = & JDispatcher::getInstance();

                                    //Import plugins for the requested plugin group:
                                    JPluginHelper::importPlugin($pluginGroup, $pluginName);

                                    //Fire the requested event:
                                    $dispatcher->trigger($pluginEvent);
                                }
                            }

                            if (is_array($log))
                                $logdata = implode("\n", $log);
                            else
                                $logdata = $log;

                            if ($value->ok)
                                $logdata .= "\n\n\n LOCAL CRON DEBUG:\n\n" . $cron->debug;

                            ########### STORE DABASE RUN TIME AND LOGS ################################
                            $query = "UPDATE #__jprccron_tasks SET ran_at= '" . $now_date . "', log_text='" . addslashes($logdata) . "', last_run = 1 WHERE id='" . $value->id . "'";
                            $database->setQuery($query);
                            if (!$database->query()) {
                                echo $database->getErrorMsg() . " on Query: " . $query . " \n";
                                exit();
                            }

                            ########## SEND EMAIL LOGS ################################################
                            if (($_CONFIG['cemail']) && ($_CONFIG['email'])) {
                                $mailer = & JFactory::getMailer();
                                $config = & JFactory::getConfig();
                                $sender = array(
                                    $config->getValue('config.mailfrom'),
                                    $config->getValue('config.fromname'));
                                $mailer->setSender($sender);
                                $mailer->addRecipient($_CONFIG['email']);

                                $body = "A cron task was failed, " . ($value->task) . " crashed when we tried to run it.";
                                $subject = $value->task . " - " . $value->file;
                                $mailer->setSubject($subject);
                                $mailer->setBody($logdata);
                                $send = & $mailer->Send();
                            }
                        }
                    }
                }
            }
    }

}

