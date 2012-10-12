{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.x                                                |
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
*}
    
<table >
<tr>
<td>
<a class="button" href="{crmURL p="civicrm/group-mapping/add" q="action=add&reset=1"}" accesskey='e'><span><div class="icon add-icon"></div>&nbsp;Add New</span></a>
</td>
</tr>
</table>

 <div>
    <table class="display" style="border:2px">
    <tr class="columnheader-dark">
     <th>Civi Groups</th> 
     <th>Drupal Terms</th> 
     <th>Send Email</th>
     <th>Actions</th> 
    </tr>
    {foreach from=$groupArray key=group_id item=groupValues} 
        {foreach from=$groupName key=id item=nameValue}        
          <tr> 
            {if $group_id eq $id }           
                <td >{$nameValue}</font></td>             
                 {foreach from=$groupValues key=email item=termsValues} 
                 {assign var=gid value=$group_id}                            
                 <td>{$termsValues} </td>
                    {if $email eq 1}
                        {assign var=emailValue value=Yes}
                    {else}
                        {assign var=emailValue value=No}
                     {/if}
                 <td align="center">{$emailValue} </td>     
                 <td><a href="{crmURL p="civicrm/group-mapping/add" q="action=update&id=$gid&reset=1"}">Edit</a>
                              <a href="{crmURL p="civicrm/group-mapping/add" q="action=delete&id=$gid&reset=1"}">Delete</a></td>           
                {/foreach}
            {/if}
          </tr> 
        {/foreach}             
    {/foreach}
    </table>
  </div>    
