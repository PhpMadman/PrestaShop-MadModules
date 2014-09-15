<div class="form-group field">
	<input class="validate form-control" placeholder="{l s='Secondary Email'}" type="text" name="cc_email" id="cc_email" value="{if isset($smarty.post.cc_email)}{$smarty.post.cc_email}{else}{$cc_email}{/if}" />
	<span class="entypo-vcard icon"></span>
	<span class="slick-tip left">{l s='Secondary Email'}</span>
</div>
