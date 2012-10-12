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

require_once 'CRM/Core/Form.php';

require_once 'CRM/Core/Session.php';

/**
 * This class provides the functionality to delete a group of
 * contacts. This class provides functionality for the actual
 * addition of contacts to groups.
 */

class CRM_Mapping_Form_Term extends CRM_Core_Form {

  function preProcess()   {	
    parent::preProcess( ); 		    
  }
    
  function buildQuickForm( ) {   
      $action = CRM_Utils_Array::value('action', $_REQUEST, '');
      $id = CRM_Utils_Request::retrieve( 'id', 'Integer', $this );
      $defaults = "";
      if ($action == 'update') {
          CRM_Utils_System::setTitle( 'Edit Group Mapping' );
          $select_sql = "SELECT * FROM  mtl_civicrm_group_mapping WHERE group_id = $id"; 
          $select_dao = CRM_Core_DAO::executeQuery($select_sql);
         
              if($select_dao->fetch()) {                    
                   $terms = $select_dao->terms; 
                   $terms = unserialize($terms);
                   if($select_dao->send_email == 1)
                        $checked = 'checked';
                   else
                        $checked = '';
                  $defaults = array(
                              'groups'   => $select_dao->group_id,
                              'terms' => $terms,
                              'send_email' =>$checked,                                                      
                              );
              }
      } elseif ($action == 'add') {
            CRM_Utils_System::setTitle( 'Add Group Mapping' );    
      } elseif ($action == 'delete'){ 
            CRM_Utils_System::setTitle( 'Delete Group Mapping' );    
            $this->assign('id', $id );       
      } elseif ($action == 'force_delete') {            
            $delete_sql = "DELETE FROM  mtl_civicrm_group_mapping WHERE group_id = $id";            
		    CRM_Core_DAO::executeQuery($delete_sql);
            $session = CRM_Core_Session::singleton( );
            $status = ts('Group Mapping deleted');
            CRM_Core_Session::setStatus( $status );
            drupal_goto( 'civicrm/group-mapping' , array ('reset' => '1' ) );     
      }       
      $this->setDefaults( $defaults );            
      $groups_sql = "SELECT * FROM civicrm_group ";
      $groups_dao = CRM_Core_DAO::executeQuery($groups_sql);
      $GroupsArray = array();
      $GroupsArray[''] = '-select-';
        while($groups_dao->fetch()) {
            $GroupsArray[$groups_dao->id] = $groups_dao->title;   
        }         
     
      /*chdir($_SERVER['DOCUMENT_ROOT']);
      include_once './includes/bootstrap.inc';
      drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);*/
      $terms = db_query('SELECT * FROM taxonomy_term_data')->fetchAllAssoc('tid');  
      $TermsArray = array();         
          foreach($terms as $key => $values){
              $TermsArray[$values->tid] = $values->name;    
          }
       $this->addElement('checkbox', 'send_email', ts('Send Email?'));     
       $this->add( 'select', 'groups', ts( 'Civi Group' ), $GroupsArray , true );  
       $this->add( 'select', 'terms', ts( 'Drupal Terms' ), $TermsArray , true, array("size"=>"5","multiple", "style" =>"width:150px") );
      //print_r($action);exit;
       $this->addElement('hidden', 'action', $action );
       $this->addElement('hidden', 'id', $id );
        
       $this->addButtons(array( 
                         array ( 'type'     => 'next', 
                                'name'      => ts('Save'), 
                                'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 
                                'isDefault' => false   ), 
                            ) 
                       ); 
             
  }
  
  
    
  public  function postProcess() {
     $submitValues = $this->_submitValues;   
     $civiGroupID = $submitValues['groups'];
     $drupalTerms = $submitValues['terms']; 
     $drupalTerms = serialize($drupalTerms);
     $send_email = "";
     if(isset($submitValues['send_email'])) {
        $send_email = $submitValues['send_email'];
     }             
               
     $select_sql = "SELECT * FROM  mtl_civicrm_group_mapping WHERE group_id = $civiGroupID"; 
     $select_dao = CRM_Core_DAO::executeQuery($select_sql);           
     if($select_dao->fetch()) {
         $update_sql = "UPDATE `mtl_civicrm_group_mapping` SET `terms`='$drupalTerms', `send_email`='$send_email' WHERE `group_id`='$civiGroupID'";  
         $update_dao = CRM_Core_DAO::executeQuery($update_sql);
         $status = ts('Group Mapping Update successfully');
     }else{
         $insert_sql = "INSERT INTO `mtl_civicrm_group_mapping`(`id`, `group_id`, `terms`,`send_email`) VALUES ('','$civiGroupID','$drupalTerms','$send_email')";           
         $insert_dao = CRM_Core_DAO::executeQuery($insert_sql); 
         $status = ts('Group Mapping Added successfully'); 
     }        
     CRM_Core_Session::setStatus( $status );   
     drupal_goto( 'civicrm/group-mapping' , array ('reset' => '1' ) );
  }


}  