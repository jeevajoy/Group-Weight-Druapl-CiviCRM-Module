<?php


 /*#############**cron job*###################***/
session_start();
require_once '../civicrm.config.php';  
require_once 'CRM/Core/Config.php';
$config = CRM_Core_Config::singleton();
CRM_Utils_System::authenticateScript(TRUE);


$run =  civicrm_group_weight_cronjob();
 

 /***function to get all groups with send email option enabled***/ 
function civicrm_group_weight_cronjob() { 
    $today = mktime('0', '0', '0', date('n'), date('j'), date('Y'));
    $content = "";     
    $sql = "SELECT d.id as ref_id , m.id as id , m.terms as terms FROM `mtl_civicrm_group_weight_daily_mapping` d 
            LEFT JOIN mtl_civicrm_group_mapping m ON m.id=d.map_id WHERE d.date=".$today;                    
    $dao = CRM_Core_DAO::executeQuery($sql); 
        
    while ($dao ->fetch()){      
      $refID = $dao->ref_id;
      $mapID =  $dao->id;  
      $terms = $dao->terms; 
      $content .= @civicrm_group_weight_get_content($refID,$mapID,$terms);                
    } 
    return $content;     
}
 
  /***function to get content of the Terms associated with group 
   *Input params groupId, corresponding Mapping ID and Terms
   * returns content   
   *****/
function civicrm_group_weight_get_content($refID,$mapID,$terms) {    
     $terms = unserialize($terms);
     $content = ""; 
     $nodes = array();    
     foreach ($terms as $key => $value ) {
        $tid = $value;
        $yday = strtotime("yesterday");  
        $today = mktime('0', '0', '0', date('n'), date('j'), date('Y'));   
                      
        $node_sql = db_query("SELECT * FROM node WHERE nid 
                              IN (SELECT nid  FROM taxonomy_index WHERE tid=$tid) AND created >= $yday")->fetchAllAssoc('nid'); 
                               
                                                           
        foreach( $node_sql as $nid => $value){  
              $nodes[$nid] = $nid;                                             
        }               
     }
      
       $content = @civicrm_group_weight_content_summary($nodes);  
       $send_email = civicrm_group_weight_send_email($refID,$mapID,$content);  
       return $content;     
}
  
  /***function to get content of the node 
   *input Params drupal Node ID  
   * returns content   
   *****/
function civicrm_group_weight_content_summary($nodes) {  
      $config = CRM_Core_Config::singleton();    
      $summary =""; 
      $term = array();      
      global $base_url; 
      foreach($nodes as $nid){
          $node = node_load($nid);
          $lang= $node->language;        
          
          if(!empty($node->field_terms)) {
            foreach($node->field_terms[$lang] as $key => $tid){
                $termDetails = taxonomy_term_load($tid['tid']);
                $term[] =$termDetails->name; 
            }  
            $terms = implode(',',$term );         
          }                  
          $body = $node->body;
          $node_title = $node->title;
         
          $body_text = $body[$lang][0]['value']; 
          //<!----Content Title ---->
          $summary  .= "Title: $node_title";
          //<!----Content Region ---->          
          $summary  .= "<br />".$terms; 
          //<!----Content Summary ---->
          $summary .= "<br />".substr($body_text, 0, 100); 
          
          $node_path = drupal_lookup_path('alias',"node/".$nid);
          if(empty($node_path)){
             $node_path ="node/".$nid; 
          }
          $node_url =$config->userFrameworkBaseURL."$node_path";  
          //<!----Content URL ---->      
          $summary .= "<br /><a href=$node_url>Read More..</a><br /><br />";  
          unset($term); 
      }  
               
     return  $summary;
}
  
  /***function to  select contacts in groups
   *input Params MappingID and associated Content  
   *****/
function civicrm_group_weight_send_email($refID,$mapID,$content) { 
      $today = mktime('0', '0', '0', date('n'), date('j'), date('Y'));     
      $sql = "SELECT * FROM civicrm_email e WHERE contact_id 
      IN(SELECT DISTINCT contact_id  FROM `civicrm_group_contact` WHERE `group_id` 
      IN (SELECT group_id FROM `mtl_civicrm_group_mapping` WHERE `id`=$mapID)) AND is_primary=1 AND  
      NOT EXISTS (SELECT  * FROM  mtl_civicrm_group_sent_email se WHERE e.contact_id = se.contact_id AND map_id=$refID AND date_sent=$today ) 
      LIMIT 0, 100";      
      $dao = CRM_Core_DAO::executeQuery($sql);      
      while ($dao ->fetch()){          
           $send =  civicrm_group_weight_send_email_template($dao,$content);    
           if($send) {                          
                $insert_sql = "INSERT INTO `mtl_civicrm_group_sent_email` (`id`, `map_id`, `contact_id`, `date_sent`) 
                               VALUES ('',$refID,$dao->contact_id,$today)";                                                         
                $insert_dao = CRM_Core_DAO::executeQuery($insert_sql);
                require_once 'api/api.php'; 
                 $params =   array ('version'          =>'3', 
                                    'activity_type_id' =>CIVICRM_MTL_GROUP_WEIGHT_ACTIVITY_TYPE_ID, 
                                    'activity_subject' =>'Email has been sent', 
                                    'source_contact_id'=>$dao->contact_id,
                                    'activity_details' =>$content
                                    );
                $create_activity=civicrm_api("Activity","create",$params );
           }
      }       
}
  
   /***function to  send email to contacts in groups
   *input Params Contact Details and associated Content  
   *****/
function civicrm_group_weight_send_email_template($Contact_Details,$content) { 
   
       $contactID = $Contact_Details->contact_id;
       $email = $Contact_Details->email;     
        
       $query = "SELECT * FROM civicrm_msg_template WHERE id =".CIVICRM_MTL_GROUP_WEIGHT_TEMPLATE_ID;     
       $dao = CRM_Core_DAO::executeQuery( $query );   
      
       if(!$dao->fetch()){ 
          print("Not able to get Email Template");
          exit;
       }
      
      $text   = $dao->msg_text;
      $html   = $dao->msg_html;
      $subject  = $dao->msg_subject; 
      require_once("CRM/Core/BAO/Domain.php");   
      $domain   = CRM_Core_BAO_Domain::getDomain();   
      
      require_once("CRM/Mailing/BAO/Mailing.php");
      $mailing = new CRM_Mailing_BAO_Mailing;
      $mailing->body_text = $text;
      $mailing->body_html = $html;      
      $tokens = $mailing->getTokens(); 
      require_once("CRM/Utils/Token.php");    
      $subject = CRM_Utils_Token::replaceDomainTokens($subject, $domain, true, $tokens['text'],true);
      $text    = CRM_Utils_Token::replaceDomainTokens($text,    $domain, true, $tokens['text'],true);
      $html    = CRM_Utils_Token::replaceDomainTokens($html,    $domain, true, $tokens['html'],true);     
      if ($contactID) {
          $subject = CRM_Utils_Token::replaceContactTokens($subject, $contact, false, $tokens['text']);
          $text    = CRM_Utils_Token::replaceContactTokens($text,    $contact, false, $tokens['text']);
          $html    = CRM_Utils_Token::replaceContactTokens($html,    $contact, false, $tokens['html']); 
      } 
        
     
      $html =  str_replace('{drupal_content}' , $content , $html);    
      $params['text']       = $text;
      $params['html']       = $html;
      $params['subject']    = $subject;   
      $params['from']       = "noreply@tuc.co.uk";
      $params['toName']     = $email;
      $params['toEmail']    = $email;
         
      require_once 'CRM/Utils/Mail.php'; 
      if(!empty($content)){  
      $sent = CRM_Utils_Mail::send( $params); 
      }
          
      return $sent;     
}

?>