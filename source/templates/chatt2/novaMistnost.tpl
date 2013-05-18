{if $chyba}
  <H2>Chyba!</H2>
  <ul>
  {foreach from=$chyba item="x" key="key" }
    {if $key == 'heslaMismatch'}<li>Hesla nejsou stejná</li>{/if}
    {if $key == 'hesloSpatne'}<li>Heslo musí být buï prázdné nebo obsahovat 5-20 alfanumerických znakù (+ mezera, "-" a "_")</li>{/if}
    {if $key == 'jmenoSpatne'}<li>Jméno musí obsahovat 3-20 alfanumerických znakù (+ mezera, "-" a "_")</li>{/if}
    {if $key == 'jmenoDuplicita'}<li>Taková místnost už existuje</li>{/if}
  {/foreach}
  </ul>
{else}
  OK
{/if}
<br><br>
<a href="{$smarty.server.SCRIPT_NAME}?akce={$smarty.request.akce}" class="other">Zpìt</a>