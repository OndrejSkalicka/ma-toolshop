<span style="text-align: center"><h2> Spr�va m�stnosti `{$mistnost->getJmeno()}`</h2></span>
<fieldset>
  <legend>Zm�na hesla</legend>
  <table>
    <form action="{$smarty.server.SCRIPT_NAME}" method="post">
      <input type="hidden" name="akce" value="{$smarty.request.akce}" />
      <input type="hidden" name="chid" value="{$smarty.request.chid}" />
      <input type="hidden" name="spravovat" value="1" />
      <tr>
        <td>Star�</td>
        <td><input type="password" name="old"/></td>
      </tr>
      <tr>
        <td>Nov�</td>
        <td><input type="password" name="new1"/></td>
      </tr>
      <tr>
        <td>Nov� znova</td>
        <td><input type="password" name="new2"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="zmenaHesla" value="Zm��"/></td>
      </tr>
    </form>
  </table>
</fieldset>
<!-- spravci -->
<fieldset>
  <legend>Spr�vci</legend>
  <table>
    <form action="{$smarty.server.SCRIPT_NAME}" method="post">
      <input type="hidden" name="akce" value="{$smarty.request.akce}" />
      <input type="hidden" name="chid" value="{$smarty.request.chid}" />
      <input type="hidden" name="spravovat" value="1" />
      <tr>
        <td colspan="3"><strong>P�idej spr�vce</strong></td>
      </tr>
      <tr>
        <td>ID:</td>
        <td><input name="newSpravceId"/></td>
        <td><input type="submit" name="newSpravce" value="Nov� spr�vce"/></td>
      </tr>
      <tr>
        <td colspan="3"><strong>Spr�vci m�stnosti</strong></td>
      </tr>
      <tr>
        <td>ID</td>
        <td>Regent</td>
        <td>Odstranit</td>
      </tr>
      {foreach from=$mistnost->getSpravci() item="spravce" }
      <tr>
        <td class="right">{$spravce.login}</td>
        <td>{$spravce.regent}, {$spravce.provi}</td>
        <td>
        <input type="checkbox" name="zrusSpravceLogin[{$spravce.id}]"/></td>
      </tr>
      {foreachelse}
      <tr>
        <td colspan="3">��dn�</td>
      </tr>
      {/foreach}
      <tr>
        <td colspan="2">&nbsp;</td>
        <td><input type="submit" name="zrusSpravce" value="Zru� spr�vce!" onClick='return window.confirm("Opravdu zru�it spr�vce?")'/></td>
      </tr>
    </form>
  </table>
</fieldset> 
<fieldset>
  <legend>Bany</legend>
  <table>
    <form action="{$smarty.server.SCRIPT_NAME}" method="post">
      <input type="hidden" name="akce" value="{$smarty.request.akce}" />
      <input type="hidden" name="chid" value="{$smarty.request.chid}" />
      <input type="hidden" name="spravovat" value="1" />
      <tr>
        <td colspan="7">
          <strong>Nov� ban!</strong>
        </td>
      </tr>
      <tr>
        <td>
          ID:
        </td>
        <td>
          <input name="newBanLogin" />
        </td>
        <td>
          d�vod:
        </td>
        <td>
          <input name="newBanReason" />
        </td>
        <td>Na jak dlouho:</td>
        <td colspan="2">{html_options options=$bananyMoznosti name="newBanExpire"}</td>
        <td><input type="submit" name="pridejBan" value="P�idej bany!" onClick='return window.confirm("Opravdu p�idat ban?")'/></td>
      </tr>
    </table>
    <table>
      <tr>
        <td colspan="6">
          <strong>Zabanovan� u�ivatel�</strong>
        </td>
      </tr>
      <tr>
        <td>ID</td>
        <td>Zabanovan� regent</td>
        <td>Autor banu</td>
        <td>D�vod</td>
        <td>Kon��</td>
        <td>Zru�it</td>
      </tr>
      {foreach from=$mistnost->getBany() item="ban" }
      <tr>
        <td class="right">{$ban.p_login}</td>
        <td>{$ban.p_regent}, {$ban.p_provi}</td>
        <td>{$ban.s_regent} ({$ban.s_login})</td>
        <td>{$ban.reason}</td>
        <td>{$ban.expire|date}</td>
        <td><input type="checkbox" name="zrusBanId[{$ban.b_id}]"/></td>
      </tr>
      {foreachelse}
      <tr>
        <td colspan="5" align="center">��dn�</td>
      </tr>
      {/foreach}
      <tr>
        <td colspan="2">&nbsp;</td>
        <td><input type="submit" name="zrusBany" value="Zru� bany!" onClick='return window.confirm("Opravdu zru�it bany?")'/></td>
      </tr>
    </form>
  </table>
</fieldset>