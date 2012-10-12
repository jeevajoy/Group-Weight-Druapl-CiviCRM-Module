<?php


 /*#############**cron job*###################***/
session_start();
require_once '../civicrm.config.php';  
require_once 'CRM/Core/Config.php';
$config = CRM_Core_Config::singleton();
CRM_Utils_System::authenticateScript(TRUE);


$run =  civicrm_group_mapping_cronjob();
 

 /***function to get all groups with send email option enabled***/ 
function civicrm_group_mapping_cronjob() { 
    $today = mktime('0', '0', '0', date('n'), date('j'), date('Y'));  
    $date = date('d-m-Y',$today);
    $content = "";     
    $sql = "SELECT * FROM `mtl_civicrm_group_mapping` WHERE  send_email = 1";                     
    $dao = CRM_Core_DAO::executeQuery($sql);     
    while ($dao ->fetch()){    
      $mapID =  $dao->id;      
      $insert_sql = "INSERT INTO `mtl_civicrm_group_weight_daily_mapping`(`id`, `map_id`, `date`) VALUES ('','$mapID','$today')";
      $insert_dao = CRM_Core_DAO::executeQuery($insert_sql); 
    }                        
}
 
  