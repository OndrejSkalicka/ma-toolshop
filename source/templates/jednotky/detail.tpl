<textarea rows="21" cols="50">{ldelim}{ldelim}Jednotka|{$detail.jmeno}
barva={if $detail.barva == 'N'}
Neutr�ln�{elseif $detail.barva == 'M'}
Modr�{elseif $detail.barva == 'B'}
B�l�{elseif $detail.barva == 'S'}
�ed�{elseif $detail.barva == 'C'}
�ern�{elseif $detail.barva == 'Z'}
Zelen�{/if}|
povolani=????|
typ={if $detail.typ == 'B'}Bojov�{else}St�eleck�{/if}|
druh={if $detail.druh == 'P'}Pozemn�{else}Leteck�{/if}|
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