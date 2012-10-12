<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2010                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 * $Id$
 *
 */

require_once 'CRM/Core/Page.php';

/**
 * This class provides the functionality to delete a group of
 * contacts. This class provides functionality for the actual
 * addition of contacts to groups.
 */

class CRM_Mapping_Page_Term extends CRM_Core_Page{

  function preProcess( ){
      CRM_Utils_System::setTitle( ts('Group Mapping') );
        
      $drupalTerms = db_query('SELECT * FROM taxonomy_term_data')->fetchAllAssoc('tid');  
      $select_sql = "SELECT * FROM  mtl_civicrm_group_mapping "; 
      $select_dao = CRM_Core_DAO::executeQuery($select_sql);          
      while($select_dao->fetch()) {          
            $terms = $select_dao->terms;
            $send_email = $select_dao->send_email; 
            if($send_email == 1)
                $sendEmail = "Yes";
            else
                $sendEmail = "No";
            $terms = unserialize($terms);
            $groups_sql = "SELECT * FROM civicrm_group WHERE id=".$select_dao->group_id;
            $groups_dao = CRM_Core_DAO::executeQuery($groups_sql);
            $groups_dao->fetch();
            foreach($terms as $key => $value) {                          
                $terms[$key]= $drupalTerms[$value]->name; 
            }
            $terms = implode("<br>",$terms);               
            $groupArray[$groups_dao->id][$select_dao->send_email] = $terms;  
            $groupName[$groups_dao->id]= $groups_dao->title; 
               
      }
      //print_r($groupArray);exit; 
      if(!empty($groupArray))
        $this->assign( 'groupArray', $groupArray );
      if(!empty($groupName)) 
        $this->assign( 'groupName', $groupName );
        
    require_once 'CRM/Core/Config.php';
    $config = CRM_Core_Config::singleton();
    $this->assign( 'userFrameworkBaseURL', $config->userFrameworkBaseURL );
  }

  
   

    /** 
     * This function is the main function that is called when the page loads, 
     * it decides the which action has to be taken for the page. 
     *                                                          
     * return null        
     * @access public 
     */                                                          
    function run( ) { 
        $this->preProcess( );        
        return parent::run( );
    }


}  