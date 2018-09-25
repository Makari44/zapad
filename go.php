
<?php
$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
if(preg_match('#(http?|ftp)://\S+[^\s.,>)\];\'\"!?]#i',$url)){
    sleep(0);
    //header("Location: $url");
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0;url=$url\"></head></html>";
    exit();
}
?>