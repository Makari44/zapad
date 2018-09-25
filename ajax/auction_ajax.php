<?php
@mysql_connect('localhost', 'root', '') or die("Не могу соединиться с MySQL.");
@mysql_select_db('farmania') or die("Не могу подключиться к базе.");
@mysql_query('SET NAMES utf8;');

$time = time() + 1;
$rows2 = mysql_query("SELECT * FROM `auction_stavki` ORDER BY `auc_id` DESC LIMIT 1");
$rows = mysql_fetch_array($rows2);

$rowes2 = mysql_query("SELECT SUM(sum) sum FROM `auction_stavki`");
$rowes = mysql_fetch_array($rowes2);

$viewss = $rows["date_del"] - time();
$pobeda = date("i минут s секунд",$viewss);

$sum_pobeda = $rowes['sum'];
$sum = $sum_pobeda*9/10;

if ($time > $rows["date_del"])
{
$user_idd = $rows['user_id'];
$user_login2 = $rows['user_login'];
mysql_query("INSERT INTO `auction_game` (`sum`, `date`, `user_login`) VALUES ('$sum','$time','$user_login2')");
mysql_query("UPDATE `users` SET `user_gold_pay`=`user_gold_pay`+'$sum' WHERE `user_id`='$user_idd' LIMIT 1");
mysql_query("TRUNCATE TABLE `auction_stavki`");
mysql_query("DELETE FROM `auction_game` WHERE `sum`='0.00'");
echo '<script type="text/javascript">
  location.replace("/auction/");
</script>';
}


echo '<center><div style="background: #ffffff; width: 350px; padding: 7px; border: 2px solid #E0BB00; border-radius: 0.4em;">
<b>Лидер аукциона</b> <a href="/profile/user/'.$rows["user_login"].'/">'.$rows["user_login"].'</a><br>
<b>Поставил:</b> '.$rows["sum"].' руб.<br>
<b>Может выиграть:</b> '.$sum.' руб.<br>
<b>До победы осталось:</b> '.$pobeda.'<br></div></center><br>';
?>