<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>LOB generator</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta name="description" content="LOB generator" />
  <link rel='STYLESHEET' type='text/css' href='styleJednotky.css' />
</head>
<body>
  <div id="outer"><div id="inner">
    <h2>Parametry jednotek k věku 5.3</h2>
    <table class="bordel">
      <tr>
        <th>jmeno</th>
        <th>typ</th>
        <th>phb</th>
        <th>ini</th>
        <th>dmg</th>
        <th>brn</th>
        <th>zvt</th>
        <th>pwr</th>
        <th>cena_zl</th>
        <th>cena_mn</th>
        <th>cena_lidi</th>
        <th>plat_zl</th>
        <th>plat_mn</th>
        <th>plat_lidi</th>
        <th>barva</th>        
      </tr>
      {foreach from=$jednotky item="jednotka"}
      <tr>
        <td style="text-align: left;">{$jednotka.jmeno}</td>
        <td>{$jednotka.druh}{$jednotka.typ}</td>
        <td>{$jednotka.phb}</td>
        <td>{$jednotka.ini}</td>
        <td>{$jednotka.dmg}</td>
        <td>{$jednotka.brn}</td>
        <td>{$jednotka.zvt}</td>
        <td>{$jednotka.pwr}</td>
        <td>{$jednotka.cena_zl}</td>
        <td>{$jednotka.cena_mn}</td>
        <td>{$jednotka.cena_lidi}</td>
        <td>{$jednotka.plat_zl}</td>
        <td>{$jednotka.plat_mn}</td>
        <td>{$jednotka.plat_lidi}</td>
        <td>{$jednotka.barva}</td> 
      </tr>
      {/foreach}
    </table>
  <div class="clear">&nbsp;</div>
  <div id="footer">Created by {mailto address="savannah@seznam.cz" text="Savannah" encode="hex" } 2006, <a href="http://validator.w3.org/check?uri=http%3A%2F%2Fsavannahsoft.eu%2Ffel%2Flob%2F">XHTML 1.0 Strict</a> :: generated in {$totalTime} ms</div>    
  </div></div>
</body>
</html>