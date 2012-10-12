{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.2                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2012                                |
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


{if $title}
<div class="crm-accordion-wrapper crm-tagGroup-accordion crm-accordion-closed">
 <div class="crm-accordion-header">
  <div class="icon crm-accordion-pointer"></div> 
	<a href="#" class="whiteanchor">{$title}</a>
  </div><!-- /.crm-accordion-header -->
  <div class="crm-accordion-body" id="tagGroup">
{/if}
 
    {if $profileID}    
    <table class="form-layout-compressed{if $context EQ 'profile'} crm-profile-tagsandgroups{/if}" style="width:98%">
      {foreach from=$elementsArray key=key item=value}
        {foreach from=$value key=key1 item=value1}
           {crmAPI var="GroupS1" entity="Group" action="get" sequential="1" id=$key1 } 
           <div id="parent">
              <br />{html_checkboxes id=$key1 values=$key1 output=$GroupS1.values.0.title separator=''} {$GroupS1.values.0.description}              
              {foreach from=$value1 key=key2 item=value2}         
                 {crmAPI var="GroupS2" entity="Group" action="get" sequential="1" id=$key2 }
                  <div id="child1"> 
                   {if $key2 neq 0}
                        {html_checkboxes id=$key2 values=$key2 output=$GroupS2.values.0.title separator=''}{$GroupS2.values.0.description} 
                   {/if} 
                  </div>
                   {foreach from=$value2 key=key3 item=value3}           
                      {foreach from=$value3 key=key4 item=value4}           
                         {crmAPI var="GroupS4" entity="Group" action="get" sequential="1" id=$key4 } 
                          <div id="child2">  
                         {if $key4 neq 0}               
                            {html_checkboxes id=$key4 values=$key4 output=$GroupS4.values.0.title separator=''}{$GroupS4.values.0.description}
                         {/if} 
                          </div> 
                </div>
                    {/foreach}              
                 {/foreach} 
            {/foreach} 
       {/foreach} 
     {/foreach}  
    </table>
    
  {else}
  <table class="form-layout-compressed{if $context EQ 'profile'} crm-profile-tagsandgroups{/if}" style="width:98%">
	<tr>     
	    {foreach key=key item=item from=$tagGroup}   
		{* $type assigned from dynamic.tpl *}
		{if !$type || $type eq $key }
		<td width={cycle name=tdWidth values="70%","30%"}><span class="label">{if $title}{$form.$key.label}{/if}</span>
		    <div id="crm-tagListWrap">
		    <table id="crm-tagGroupTable">             
			{foreach key=k item=it from=$form.$key}            
			    {if $k|is_numeric}    
				<tr class={cycle values="'odd-row','even-row'" name=$key} id="crm-tagRow{$k}">
				    <td>                                              
		         	<strong>{$it.html} </strong><br />                          
					{if $item.$k.description}
					    <div class="description">
						{$item.$k.description}
					    </div>
					{/if} 
				    </td> 
				</tr>
			    {/if} 
            {/foreach}               
		    </table>
		    </div>
		</td>
		{/if} 
	  {/foreach}
       
	</tr>
    
	<tr><td>{include file="CRM/common/Tag.tpl"}</td></tr>
    </table>  
  {/if}  
     
{if $title}
 </div><!-- /.crm-accordion-body -->
</div><!-- /.crm-accordion-wrapper -->

{/if}
 <style type="text/css">
 {literal} 

 #child2{  
  position:relative;
  right: -40px;
  padding:0px;
}
 #child1{ 
  position:relative;
  right: -20px;
  padding:0px;
}
 #parent{   
  position:relative;
  right: 0px;
  padding:0px;
} 
 {/literal} 
 </style>
 
 {literal}
<script type="text/javascript">
cj(document).ready(function() {   
 alert  

});
</script>
{/literal}
