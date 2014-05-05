{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="field"}
	{if $input.type == 'three_list'}
		{foreach $input.lists as $list}
			<div style="width:33%;float:left;text-align:center;">
				<label for="{$list.id}">{$list.label}</label>
				<select size="10"  id="{$list.id}">
					{foreach $list.selectlist as $option}
						<option value="{$option.id}">{foreach $list.option_syntax as $syntax}{$option[$syntax['key']]}{$syntax['seperator']}{/foreach}</option>
					{/foreach}
				</select>
				{foreach $list.move as $move}
					<button class="btn btn-default" name="Move" type="button" onclick="MoveProduct('{$list.id}','{$move.to_id}');return false;">
						{if $move.direction == left}
							<i class="process-icon-back" style="display:inline;"></i>
							{$move.text}
						{else}
							{$move.text}
							<i class="process-icon-next" style="display:inline;"></i>
						{/if}
					</button>
				{/foreach}
			</div>
		{/foreach}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
