<?php

require_once 'utmaltor.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function utmaltor_civicrm_config(&$config) {
  _utmaltor_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function utmaltor_civicrm_xmlMenu(&$files) {
  _utmaltor_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function utmaltor_civicrm_install() {
  _utmaltor_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function utmaltor_civicrm_uninstall() {
  _utmaltor_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function utmaltor_civicrm_enable() {
  _utmaltor_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function utmaltor_civicrm_disable() {
  _utmaltor_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function utmaltor_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _utmaltor_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function utmaltor_civicrm_managed(&$entities) {
  _utmaltor_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function utmaltor_civicrm_caseTypes(&$caseTypes) {
  _utmaltor_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function utmaltor_civicrm_angularModules(&$angularModules) {
_utmaltor_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function utmaltor_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _utmaltor_civix_civicrm_alterSettingsFolders($metaDataFolders);
}


function utmaltor_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Mailing' and $op == 'edit') {
    $domains = CRM_Core_BAO_Setting::getItem('UTMaltor Preferences', 'utmaltor_domains');
    $domains = str_replace('.', '\.', $domains);
    $pattern = '/href="(http[^\s"]+(' . $domains . ')[^\s"]*)/imu';
    preg_match_all($pattern, $params['body_html'], $matches);
    $urls = array();
    if (is_array($matches[1]) && count($matches[1])) {
      $utmSmarty = CRM_Utmaltor_Logic_Smarty::singleton($params);
      foreach ($matches[1] as $url) {
        $urls[$url] = CRM_Utmaltor_Logic_Alter::url($url, $utmSmarty);
      }
    }
    foreach ($urls as $old => $new) {
      if ($old != $new) {
        $params['body_html'] = str_replace('href="'.$old.'"', 'href="'.$new.'"', $params['body_html']);
      }
    }
  }
}


function utmaltor_civicrm_alterUrl($url, $params) {
  $utmSmarty = CRM_Utmaltor_Logic_Smarty::singleton($params);
  $re = '/href="(.*)"/';
  if (preg_match($re, $url, $urlMatches)) {
    $url = CRM_Utmaltor_Logic_Alter::url($urlMatches[1], $utmSmarty);
    $url = "href='" . $url . "'";
  } else {
    $url = CRM_Utmaltor_Logic_Alter::url($url, $utmSmarty);
  }
}
