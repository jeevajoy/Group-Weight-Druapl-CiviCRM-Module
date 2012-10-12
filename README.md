====================================================
CiviCRM Group Weight Module - Installation and Setup
====================================================

Requirements
---------------------------------------------

This module requires CiviCRM 4.0.x or greater and Drupal 7.x.

Installation Instructions
---------------------------------------------

To install the module, move the `civicrm_group_weight` directory to your standard drupal modules directory.

Import additional tables
---------------------------------------------

To import additional tables, please import the file 'import.sql' to your civicrm database.

Enable the Module
---------------------------------------------

Navigate to Drupal > Administer > Site building > Modules and enable CiviCRM Group Weight Module module.

Refer to the Drupal documentation for further information on how to install modules.

Set CiviCRM Profile ID
---------------------------------------------

To display the groups in weight order in a CiviCRM profile, the profile ID needs to be set in the module file, 
so that the groups list only in that profile is displayed in weight order. This is to make sure that other
CiviCRM profile are not affected by this module. 

To set the profile to with groups to be sorted by weight,     

Open 'civicrm_group_weight.module' file under civicrm_group_weight directory using any text editor.

On line number 39, replace the value with CiviCRM Profile ID, in which the groups are to be displayed in weight order  

In our example it will be

define( 'CIVICRM_MTL_GROUP_WEIGHT_PROFILE_ID' , 15 );

Please make sure the profile have Groups field included.

Set Up Message Template
---------------------------------------------
                               
To setup the mail template for Drupal Email Notification, Navigate to   

CiviCRM >> Administer >> CiviMail >> Message Templates 
    
Add Message Template

The template details are in template.txt file

Set Up Message Template ID in module
---------------------------------------------

Open 'civicrm_group_weight.module' file under civicrm_group_weight directory using any text editor.

On line number 40, replace the value with created Message template ID 

In our example it will be

define( 'CIVICRM_MTL_GROUP_WEIGHT_TEMPLATE_ID' , 45 );

Set From Email address
--------------------------------------------- 

To setup the from email address for the Drupal email notifications, open the file 'civicrm_group_weight.module'with a text editor and change the email address in line number 38. 

For example, to send emails from ‘bfurber@tuc.org.uk’, the line should be like below. In this case, all the drupal email notifications going through the module will have from email address as ‘bfurber@tuc.org.uk’

define( 'CIVICRM_MTL_GROUP_WEIGHT_FROM_EMAIL_ADDRESS' , 'bfurber@tuc.org.uk');

Set Activity Type 
---------------------------------------------

For every drupal notification email sent, an activity is created under the contact record.   
 
To create a new activity type ,Navigate to
 
CiviCRM >> Administer >> Option Lists >> Activity Types

Add Activity Type - Example: Drupal email notification  

Open 'civicrm_group_weight.module' file under civicrm_group_weight directory using any text editor.

On line number 41, replace the value with newly created Activity Type Value

In our example it will be

define( 'CIVICRM_MTL_GROUP_WEIGHT_ACTIVITY_TYPE_ID' , 33 );

Cron Jobs - Setup
---------------------------------------------

1.	Cron job to determine which mapping records will receive emails for the day. 

IMPORTANT: This cron job needs to be run only once a day at 12 AM. 

Set up the cron job to trigger the below script/URL for once in a day (replace the relevant parts with your credentials).

http://[your_root_url]/sites/all/modules/contrib/civicrm/bin/MTLGetMapping.php?name=<username>&pass=<password>&key=<site_key>

2.	Cron job to send the drupal notification emails out in staggered batches (100 email at a time)

IMPORTANT: This cron job needs to be for every 5 or 10 minutes.

This cron job will check for the mapping records marked (by the above cron job) and send the emails out for the contacts in the related groups. No emails will be sent out, if all the contacts in the group have received emails for the day, even though the script runs every 5 or 10 minutes. 

Set up the cron job to trigger the below script/URL for every 5 or 10 minutes (replace the relevant parts with your credentials).

http://[your_root_url]/sites/all/modules/contrib/civicrm/bin/ MTLSendEmailsForMapping.php?name=<username>&pass=<password>&key=<site_key>

WHERE

<username> - SOAP user’s username
<password> - SOAP user’s password
<site_key> - CiviCRM Site Key. You can find the site key in civicrm.settings.php

Edit Drupal content format in email (For programmers only)
--------------------------------------------- 
	
The script which composes drupal content and replaces in the email can be found in ‘/u01/www/www.unionlearn.org.uk/htdocs/sites/all/modules/contrib/civicrm/bin /MTLSendEmailsForMapping.php’ file around line number 83
Below is the explanation of the code

//<!----Content Title ---->
$summary  .= "Title: $node_title";      - This adds the drupal content title to the email
//<!----Content Region ---->          
$summary  .= "<br>".$terms; 	        - This adds the drupal content region/terms to the email
//<!----Content Summary ---->
$summary .= "<br>".substr($body_text, 0, strpos(wordwrap($body_text, 300), "\n")).'...';
                                        - This adds the drupal content body, trimmed to 300 characters to the email

The above code is in a loop and is repeated, if more drupal content are found. For more help in editing the code to modify the email format, please contact rajesh@millertech.co.uk

More Information
---------------------------------------------

For more information about process flow or how to use, please refer to 'TUC_Workflow.docx' inside the module folder.

Contact Information
---------------------------------------------

All feedback and comments of a technical nature (including support questions)
and for all other comments you can reach me at the following e-mail address. Please
include "CiviCRM Group Weight Module" somewhere in the subject.

rajesh AT millertech.co.uk

License Information
---------------------------------------------

Copyright (C) Miller Technology 2012
    
    
    
         
  

