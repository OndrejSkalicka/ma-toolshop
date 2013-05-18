{literal}
<script language="JavaScript" type="text/javascript">
<!--
function adt(text) {
	var js_txtarea = document.post.message;
	if (js_txtarea.createTextRange && js_txtarea.caretPos) {
		var js_caretPos = js_txtarea.caretPos;
		js_caretPos.text = js_caretPos.text.charAt(js_caretPos.text.length - 1) == ' ' ? js_caretPos.text + text + ' ' : js_caretPos.text + text;
		js_txtarea.focus();
	} else {
		js_txtarea.value  += text;
		js_txtarea.focus();
	}
}
function bananKvete (login, id) {
  if (document.getElementById) {
    js_banan = document.getElementById("banany"); 
    js_sprava = document.getElementById("spravy"); 
    var js_input1 = document.bananovnik.bananId;
    var js_input2 = document.bananovnik.bananLogin;
    
    if (js_banan.style.display == "block") {
      js_input1.value = "";  
      js_input2.value = "";  
      
      js_banan.style.display = "none";
      js_sprava.style.display = "none";
    } else {
      js_input1.value = id;  
      js_input2.value = login;  
     		
  		js_banan.style.display = "block";
  		js_sprava.style.display = "none";
    }    
	}
}
function bananVadne () {
  if (document.getElementById) { 
    var js_input1 = document.bananovnik.bananId;
    js_input1.value = "";  
    var js_input2 = document.bananovnik.bananLogin;
    js_input2.value = "";    
		js_banan = document.getElementById("banany"); 
		js_banan.style.display = "none";
	}
	
	return false;
}
function spravaKvete (login, id, grant) {
  if (document.getElementById) { 
    js_sprava = document.getElementById("spravy"); 
    js_banan = document.getElementById("banany"); 
    var js_input1 = document.sprava.spravaLogin;
    var js_input2 = document.sprava.spravaId;
    if (js_sprava.style.display == "block") {
      js_input2.value = "";  
      js_input1.value = "";
  		
  		js_sprava.style.display = "none";
  		js_banan.style.display = "none";
    } else {
      js_input2.value = id;  
      js_input1.value = login;  
      if (grant == 0) {
        var js_radio = document.sprava.grantDeny[0];
        js_radio.checked = "checked";
      } else {
        var js_radio = document.sprava.grantDeny[1];
        js_radio.checked = "checked";
      }  
  		
  		js_sprava.style.display = "block";
  		js_banan.style.display = "none";
    }
	}
}
function spravaVadne () {
  var js_input = document.sprava.spravaId;
  js_input.value = "";  
  if (document.getElementById) { 
		js_sprava = document.getElementById("spravy"); 
		js_sprava.style.display = "none";
	}
	
	return false;
}

function reloadChatt() {
  
	var js_typed = document.post.message.value;
	if (document.getElementById) { 
    js_banan = document.getElementById("banany"); 
	  js_sprava = document.getElementById("spravy");
  } 
	
  if ( (!js_typed) && (js_banan.style.display != "block") && (js_sprava.style.display != "block")) {
    {/literal}location.replace("main.php?akce=chatt&chid={$mistnost->getId()}");
  {rdelim}
{rdelim}

window.setInterval("reloadChatt()", {math equation="$refresh * 1000" });
//-->
</script>
<h1 style="text-align: center;">Welcome to `{$mistnost->getJmeno()}`</h1>
<div id="messages_middle">
  <div id="messages_box">
    <form action="{$smarty.server.SCRIPT_NAME}" method="post" name="post">
      <input type="hidden" name="chid" value="{$mistnost->getId()}" />
      <input type="hidden" name="akce" value="chatt" />
      
      <div class="left">
        <table border="0" cellspacing="0" cellpadding="5">
				<tr align="center" valign="middle">
				{foreach from=$smileList item="smile" key="key" }
          <td><a href="javascript:adt('{$smile}')"><img src="img/smiles/{$key}.png" border="0" alt="{$smile}" title="{$smile}" /></a></td>
          {if $key == 5 || $key == 10}
        </tr>
        <tr align="center" valign="middle">
          {/if}
        {/foreach}
			</table>
      </div>
      
      <div class="right">
        <table>
          <tr>
            <td colspan="5">
              <textarea name="message" rows="5" cols="35" wrap="virtual"></textarea>
            </td>
          </tr>
          <tr>
            <td><input class="customButton" type="submit" name="send" value="Send" />
            </td>
            <td>
              Zpráv na stranu:
            </td>
            <td>
              {html_options output=$zpravNaStranuMoznosti values=$zpravNaStranuMoznosti name="ch2_zpravNaStranu" selected=$zpravNaStranu}
            </td>
            <td>
              Refresh rate:
            </td>
            <td>
              {html_options options=$refreshMoznosti name="ch2_refresh" selected=$refresh}
            </td>
          </tr>
        </table>
      </div>
    </form>
    <div class="clear">&nbsp;</div>
  </div>
  <table id="messages_display">
  {foreach from=$mistnost->getPrispevky() item="prispevek" }
    <tr>
      <td>
        <div class="w_left">
          <a href="javascript:adt('{$prispevek->getAutor('regent')} : ')">{$prispevek->getAutor('regent')|truncate:15:"..."}</a> ({$prispevek->getAutor('login')})
        </div>
      </td>
      <td>
        <div class="w_middle">
          {$prispevek->getText()}
        </div>
      </td>
      <td>
        <div class="w_right">
          {$prispevek->getTime('H:i d/m/y')}
          {if $prispevek->getTime() > $chattUzivatel->getLastVisit()}<img src="img/chatt/new.png" alt="NEW" title="NEW" width="30" height="12" />{/if}
        </div>
      </td>        
    </tr>
  {/foreach}
  </table>  
</div>
<div id="messages_whoisonline">
  <h3>Kdo je online:</h3>
  <table>
  {foreach from=$mistnost->whoIsOnline() item="onlineUser" }
    <tr>
      <td colspan="4"> <!-- nick -->
        {if $onlineUser.provi}{$onlineUser.regent|truncate:15:"..."}, {$onlineUser.provi|truncate:15:"..."}{else}{$onlineUser.regent|truncate:30:"..."}{/if} ({$onlineUser.login})
      </td>
    </tr>
    {if $onlineUser.pwr}
    <tr>
      <td colspan="4" style="text-align: center;"> <!-- pwr -->
        {$onlineUser.pwr|number_format} pwr
      </td>
    </tr>
    {/if}
    <tr style="text-align: center;">
      <td> <!-- ICQ -->
        {if $onlineUser.icq > 0}<a href="http://www.icq.com/people/cmd.php?uin={$onlineUser.icq}&action=message" class="icq"><img src="img/chatt/icq2.png" alt="{$onlineUser.icq|number_format:0:'':' '}" title="ICQ: {$onlineUser.icq|number_format:0:'':' '}" width="16" height="16" />
        {else}&times;{/if}
      </td>
      <td> <!-- special rank -->
        {if $onlineUser.superUser}<img src="./img/chatt/super_user.png" alt="[X]" title="administrátor" width="15" height="15" />
        {elseif $onlineUser.vlastnik}<img src="./img/chatt/vlastnik.png" alt="[V]" title="vlastník" width="15" height="15" />
        {elseif $onlineUser.spravce}<img src="./img/chatt/spravce.png" alt="[S]" title="správce" width="15" height="15" />
        
        {else}&times;{/if}
      </td>
      {* vlastnik *}
      {if $uzivatelNarok == 'vlastnik'}
        <td> <!-- banany -->
          {if not $onlineUser.vlastnik}
            <a href="javascript:bananKvete({$onlineUser.login},{$onlineUser.id})"><img src="./img/chatt/ban.png" alt="[B]" title="Ban" width="15" height="15" /></a>
          {else}&times;{/if}
        </td>
        <td> <!-- spravcovstvi -->
          {if not $onlineUser.vlastnik}
            {if $onlineUser.spravce}
              <a href="javascript:spravaKvete({$onlineUser.login},{$onlineUser.id},1)"><img src="./img/chatt/stopSpravce.png" alt="[xS]" title="Zruš správcovství" width="15" height="15" /></a>
            {else}<a href="javascript:spravaKvete({$onlineUser.login},{$onlineUser.id},0)"><img src="./img/chatt/makeSpravce.png" alt="[S]" title="Udìlej správcem" width="15" height="15" /></a>{/if}
          {else}&times;{/if}
        </td>
      {elseif $uzivatelNarok == 'spravce'}
        <td>
          <td> <!-- banany -->
          {if not $onlineUser.vlastnik and not $onlineUser.spravce}
            <a href="javascript:bananKvete({$onlineUser.login},{$onlineUser.id})"><img src="./img/chatt/ban.png" alt="[B]" title="Ban" width="15" height="15" /></a>
          {else}&times;{/if}
        </td>
        </td>
      {/if}
    </tr>
  {foreachelse}
    <tr><td>
    Nikdo (coz je trochu ... blbost, ani ty :-))</td></tr>  
  {/foreach}
  </table>
  <div id="banany">
  {if $uzivatelNarok == 'spravce' or $uzivatelNarok == 'vlastnik'}
    
      <div class="head">
        Trafika s banánama
      </div>
      <form action="{$smarty.server.SCRIPT_NAME}" method="post" name="bananovnik">
        <input type="hidden" name="chid" value="{$mistnost->getId()}" />
        <input type="hidden" name="akce" value="chatt" />
        <div class="warning">
          {if $uzivatelNarok == 'spravce'}
            Mìjte prosím na pamìti, že jako správci NEMÙŽETE Banána zrušit! To 
            mùže udìlat pouze vlastník této místnosti. <br><br>
            !! Pokud mátì otevøené toto okno, tak se vám automaticky neobnovuje místnost !!
          {else}
            Mìjte prosím na pamìti, že pokud dáte Banána správci, ten automaticky 
            o své správcovtsví pøijde!<br><br>
            !! Pokud mátì otevøené toto okno, tak se vám automaticky neobnovuje místnost !!
          {/if}          
        </div>
        <table>
          <tr>
            <td>ID:</td>
            <td colspan="2"><input name="bananId" type="hidden" /><input name="bananLogin" readonly="readonly" /></td>
          </tr>
          <tr>
            <td>Na jak dlouho:</td>
            <td colspan="2">{html_options options=$bananyMoznosti name="expire"}</td>
          </tr>
          <tr>
            <td>Dùvod:</td>
            <td colspan="2"><input name="reason" /></td>
          </tr>
          <tr>
            <td colspan="2"><input type="button" onclick='javascript:bananVadne();return false;' value="Stormo" /></td>
            <td style="text-align: right"><input type="submit" name="banan" value="Banán!" onClick='return window.confirm("Opravdu dáte Banána?")'></td>
          </tr>
        </table>
        
        
        
      </form>
      
    
  {/if}
  </div>
  <div id="spravy">
  {if $uzivatelNarok == 'vlastnik'}
    
      <div class="head">
        Správci
      </div>
      <form action="{$smarty.server.SCRIPT_NAME}" method="post" name="sprava">
        <input type="hidden" name="chid" value="{$mistnost->getId()}" />
        <input type="hidden" name="akce" value="chatt" />
        <div class="warning">
          !! Pokud mátì otevøené toto okno, tak se vám automaticky neobnovuje místnost !!          
        </div>
        <table>
          <tr>
            <td>ID:</td>
            <td colspan="2"><input name="spravaId" type="hidden" />
            <input name="spravaLogin" readonly="readonly" /></td>
          </tr>
          <tr>
            <td colspan="3">
              <input style="border: none;" type="radio" name="grantDeny" value="grant" id="grant"/><label for="grant">Udìlej správcem</label>
              <input style="border: none;" type="radio" name="grantDeny" value="deny" id="deny"/><label for="deny">Zruš správce</label>
            </td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" onclick='javascript:spravaVadne();return false;' value="Stormo" /></td>
            <td style="text-align: right"><input type="submit" name="sprava" value="Nastav!" onClick='return window.confirm("Opravdu?")'></td>
          </tr>
        </table>
      </form>
    
  {/if}
  </div>
</div>
<div class="clear">&nbsp;</div>