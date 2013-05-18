<textarea rows="21" cols="50">{ldelim}{ldelim}Jednotka|{$detail.jmeno}
barva={if $detail.barva == 'N'}
Neutrální{elseif $detail.barva == 'M'}
Modrá{elseif $detail.barva == 'B'}
Bílá{elseif $detail.barva == 'S'}
Šedá{elseif $detail.barva == 'C'}
Èerná{elseif $detail.barva == 'Z'}
Zelená{/if}|
povolani=????|
typ={if $detail.typ == 'B'}Bojová{else}Støelecká{/if}|
druh={if $detail.druh == 'P'}Pozemní{else}Letecká{/if}|
phb={$detail.phb}|
pwr={$detail.pwr}|
ini={$detail.ini}|
cena_zl={$detail.cena_zl}|
cena_mn={$detail.cena_mn}|
cena_pp={$detail.cena_lidi}|
upkeep_zl={$detail.plat_zl}|
upkeep_mn={$detail.plat_mn}|
upkeep_pp={$detail.plat_lidi}|
dmg_pm=???|
brn_pm=???|
zvt_pm=???|
dmg_abs={$detail.dmg}|
brn_abs={$detail.brn}|
zvt_abs={$detail.zvt}{rdelim}{rdelim}</textarea><br />