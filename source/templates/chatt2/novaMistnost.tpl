{if $chyba}
  <H2>Chyba!</H2>
  <ul>
  {foreach from=$chyba item="x" key="key" }
    {if $key == 'heslaMismatch'}<li>Hesla nejsou stejn�</li>{/if}
    {if $key == 'hesloSpatne'}<li>Heslo mus� b�t bu� pr�zdn� nebo obsahovat 5-20 alfanumerick�ch znak� (+ mezera, "-" a "_")</li>{/if}
    {if $key == 'jmenoSpatne'}<li>Jm�no mus� obsahovat 3-20 alfanumerick�ch znak� (+ mezera, "-" a "_")</li>{/if}
    {if $key == 'jmenoDuplicita'}<li>Takov� m�stnost u� existuje</li>{/if}
  {/foreach}
  </ul>
{else}
  OK
{/if}
<br><br>
<a href="{$smarty.server.SCRIPT_NAME}?akce={$smarty.request.akce}" class="other">Zp�t</a>