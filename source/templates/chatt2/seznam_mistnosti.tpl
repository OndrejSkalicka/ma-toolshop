<h2 style="text-align: center">Chat - seznam místností</h2>
<a class="other" href="http://forum.savannahsoft.eu/viewtopic.php?t=211" style="color: red; text-decoration: underline;">VÍCE INFO NA FORU</a><br><br>
<fieldset>
  <legend>Vstoupit do existující</legend>
  <form style="margin-left: 25px;" action="{$smarty.server.SCRIPT_NAME}" method="post">
    <input type="hidden" name="akce" value="{$smarty.request.akce}" />
    <table>
      <tr>
        <td>Jméno místnosti:</td>
        <td><input name="vstupJmeno" /></td>
      </tr>
      <tr>
        <td>Heslo (pokud je nastaveno):</td>
        <td><input type="password" name="vstupHeslo" />
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Vstup!" class="customButton" /></td>
      </tr>            
    </table>
  </form>
</fieldset>
<fieldset>
  <legend>Oblíbené (navštívené za poslední týden krom tìch kde máte BAN)</legend>
  <table style="margin-left: 25px;">
  {foreach from=$chattUzivatel->getOblibeneMistnosti() item="obMi" }
    <tr>
      <td>{$obMi->getJmeno()}</td>
      <td class="right">{if $obMi->getNewMsg() > 0}{$obMi->getNewMsg()} <img src="img/chatt/new.png" alt="NEW" title="NEW" width="30" height="12" />{else}&nbsp;{/if}</td>
      <td><a class="other" href="{$smarty.server.SCRIPT_NAME}?akce={$smarty.request.akce}&amp;chid={$obMi->getId()}">[vstoupit]</a></td>
    </td>
  {foreachelse}
    <tr><td>Žádné</td></tr>
  {/foreach}
  </table>
</fieldset>
<fieldset>
  <legend>Spravujete</legend>
  <table style="margin-left: 25px;">
  {foreach from=$chattUzivatel->getSpravovaneMistnosti() item="spMi" }
    <tr>
      <td>{$spMi->getJmeno()}</td>
      <td class="right">{if $spMi->getNewMsg() > 0}{$spMi->getNewMsg()} <img src="img/chatt/new.png" alt="NEW" title="NEW" width="30" height="12" />{else}&nbsp;{/if}</td>
      <td><a class="other" href="{$smarty.server.SCRIPT_NAME}?akce={$smarty.request.akce}&amp;chid={$spMi->getId()}">[vstoupit]</a></td>
    </td>
  {foreachelse}
    <tr><td>Žádné</td></tr>
  {/foreach}
  </table>
</fieldset>
<fieldset>
  <legend>Vlastníte</legend>
  <table style="margin-left: 25px;">
  {foreach from=$chattUzivatel->getVlastniMistnosti() item="vlMi" }
    <tr>
      <td>{$vlMi->getJmeno()}</td>
      <td class="right">{if $vlMi->getNewMsg() > 0}{$vlMi->getNewMsg()} <img src="img/chatt/new.png" alt="NEW" title="NEW" width="30" height="12" />{else}&nbsp;{/if}</td>
      <td><a class="other" href="{$smarty.server.SCRIPT_NAME}?akce={$smarty.request.akce}&amp;chid={$vlMi->getId()}">[vstoupit]</a></td> 
      <td><a class="other" href="{$smarty.server.SCRIPT_NAME}?akce={$smarty.request.akce}&amp;chid={$vlMi->getId()}&amp;spravovat=1">[spravovat]</a></td>
    </td>
  {foreachelse}
    <tr><td>Žádné</td></tr>
  {/foreach}
  </table>
</fieldset>
<fieldset>
  <legend>Založit novou</legend>
    <form style="margin-left: 25px;" action="{$smarty.server.SCRIPT_NAME}" method="post">
    <input type="hidden" name="akce" value="{$smarty.request.akce}" />
    <table>
      <tr>
        <td>Jméno místnosti:</td>
        <td><input name="novaJmeno" /></td>
      </tr>
      <tr>
        <td>Heslo (mùže být prázdné):</td>
        <td><input type="password" name="novaHeslo" />
        </td>
      </tr>
      <tr>
        <td>Heslo znovu:</td>
        <td><input type="password" name="novaHeslo2" />
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="novaMistnost" value="Vytvoø!" class="customButton" /></td>
      </tr>            
    </table>
  </form>
</fieldset>