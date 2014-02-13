 <style type="text/css">
	.td_text {
		width: 100px;
		font-size: 14px;
	}
	.td_input {
		font-size:12px;
		height:20px;
		width: 205px;
	}
	.td_input input {
		width: 200px;
		border: 1px solid #000000;
	}
</style>
<script>
$(document).ready(function() {
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function(e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
               e.target.setCustomValidity(e.target.getAttribute("data-requiredmsg"));
            }
        };
        elements[i].oninput = function(e) {
            e.target.setCustomValidity("");
        };
    }
})
</script>

<form name="bookvisit" method="post" action="{$request_uri|escape:'htmlall':'UTF-8'}">

	<span style="font-size:14px;">Vill du veta mer om våra produkter och lösningar? Boka ett kundbesök idag. <br></span><br>

	<table>
	<tr>
		<td class="td_text">Företag</td>
		<td class="td_input"><input type="text" name="company" required autofocus data-requiredmsg="Du måste ange ett företagsnamn"/>
		</td>
	</tr>
	<tr>
		<td class="td_text">Kontaktperson</td>
		<td class="td_input"><input type="text" name="contact" required data-requiredmsg="Du måste ange en kontaktperson" /></td>
	</tr>
	<tr>
		<td class="td_text">Adress</td>
		<td class="td_input"><input type="text" name="address" required data-requiredmsg="Du måste ange en address" /></td>
	</tr>
	<tr>
		<td class="td_text">Postnr</td>
		<td class="td_input"><input type="text" name="postal_code" required data-requiredmsg="Du måste ange ett postnr" /></td>
	</tr>
	<tr>
		<td class="td_text">Stad</td>
		<td class="td_input"><input type="text" name="city" required data-requiredmsg="Du måste ange en stad" /></td>
	</tr>
	<tr>
		<td class="td_text">Telefon</td>
		<td class="td_input"><input type="tel" name="phone" required data-requiredmsg="Du måste ange ett telefonnummer" /></td>
	</tr>
	<tr>
		<td class="td_text">E-Mail</td>
		<td class="td_input"><input type="email" name="email" required data-requiredmsg="Du måste ange en e-mail address" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="bookit" value="Beställ besök" class="button" /></td>
	</tr>
	</table>
</form>
<br><br><br>
{$bookvisit->DoForm($smarty.post)}