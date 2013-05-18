<span style="text-align: center"><h2> Správa místnosti `{$mistnost->getJmeno()}`</h2></span>
<fieldset>
  <legend>Zmìna hesla</legend>
  <table>
    <form action="{$smarty.server.SCRIPT_NAME}" method="post">
      <input type="hidden" name="akce" value="{$smarty.request.akce}" />
      <input type="hidden" name="chid" value="{$smarty.request.chid}" />
      <input type="hidden" name="spravovat" value="1" />
      <tr>
        <td>Staré</td>
        <td><input type="password" name="old"/></td>
      </tr>
      <tr>
        <td>Nové</td>
        <td><input type="password" name="new1"/></td>
      </tr>
      <tr>
        <td>Nové znova</td>
        <td><input type="password" name="new2"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="zmenaHesla" value="Zmìò"/></td>
      </tr>
    </form>
  </table>
</fieldset>
<!-- spravci -->
<fieldset>
  <legend>Správci</legend>
  <table>
    <form action="{$smarty.server.SCRIPT_NAME}" method="post">
      <input type="hidden" name="akce" value="{$smarty.request.akce}" />
      <input type="hidden" name="chid" value="{$smarty.request.chid}" />
      <input type="hidden" name="spravovat" value="1" />
      <tr>
        <td colspan="3"><strong>Pøidej správce</strong></td>
      </tr>
      <tr>
        <td>ID:</td>
        <td><input name="newSpravceId"/></td>
        <td><input type="submit" name="newSpravce" value="Nový správce"/></td>
      </tr>
      <tr>
        <td colspan="3"><strong>Správci místnosti</strong></td>
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
        <td colspan="3">Žádní</td>
      </tr>
      {/foreach}
      <tr>
        <td colspan="2">&nbsp;</td>
        <td><input type="submit" name="zrusSpravce" value="Zruš správce!" onClick='return window.confirm("Opravdu zrušit správce?")'/></td>
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
          <strong>Nový ban!</strong>
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
          dùvod:
        </td>
        <td>
          <input name="newBanReason" />
        </td>
        <td>Na jak dlouho:</td>
        <td colspan="2">{html_options options=$bananyMoznosti name="newBanExpire"}</td>
        <td><input type="submit" name="pridejBan" value="Pøidej bany!" onClick='return window.confirm("Opravdu pøidat ban?")'/></td>
      </tr>
    </table>
    <table>
      <tr>
        <td colspan="6">
          <strong>Zabanovaní uživatelé</strong>
        </td>
      </tr>
      <tr>
        <td>ID</td>
        <td>Zabanovaný regent</td>
        <td>Autor banu</td>
        <td>Dùvod</td>
        <td>Konèí</td>
        <td>Zrušit</td>
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
        <td colspan="5" align="center">Žádní</td>
      </tr>
      {/foreach}
      <tr>
        <td colspan="2">&nbsp;</td>
        <td><input type="submit" name="zrusBany" value="Zruš bany!" onClick='return window.confirm("Opravdu zrušit bany?")'/></td>
      </tr>
    </form>
  </table>
</fieldset>