<?php
$users = array(
    'savannah' => 'cNEZZ2slxF',
    'razor' => 'cX2MrY9roh',
);

if (!array_key_exists($_SERVER['PHP_AUTH_USER'], $users) || $users[$_SERVER['PHP_AUTH_USER']] != $_SERVER['PHP_AUTH_PW']) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="content-language" content="cs"/>
    <title>MA TOOLSHOP RESET</title>
</head>
<body>
<?php
require '../dblogin.php';

function q($q) {
    echo '<pre>';
    mysql_query($q);
    if (mysql_error()) {
        echo mysql_error(), '<br/>';
    } else {
        echo "$q ... OK<br/>";
    }
    echo '</pre>';
}

if ($_POST['submit']) {
    q('TRUNCATE hlidka_log;');
    q('UPDATE `users` SET zlato = 0, simul_count = 0, aukce_count = 0, hlidka_count = 0,
        hlidka_last_update = 0, hlidka_pocet_zachran = 0, last_pwr = 0;');
    q('TRUNCATE hlidka_hodiny;');
    q("INSERT INTO veky (priorita, jmeno, `title`) VALUES (-1, '" .
            mysql_real_escape_string($_POST['jmeno']) . "', '" . mysql_real_escape_string($_POST['title']) . "')");
    q('UPDATE veky SET priorita = priorita + 1');
}

// nejvyssi vek
$h = mysql_query('SELECT jmeno FROM veky ORDER BY priorita ASC LIMIT 1');
$a = mysql_fetch_array($h);
$jmeno = $a[0];

preg_match('#(.*\.)(\d+)#', $jmeno, $matches);
$jmeno = $matches[1] . ($matches[2] + 1);

?>
<form action="." method="POST">
    <table>
        <tr>
            <th>Jmeno veku:</th>
            <td><input type="text" name="jmeno" value="<?php echo $jmeno;?>"/></td>
        </tr>
        <tr>
            <th>Nadpis:</th>
            <td><input type="text" name="title" value="unknown"/></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                <input type="submit" name="submit" value="RESET + NOVY VEK"/></td>
        </tr>
    </table>
</form>

</body>
</html>