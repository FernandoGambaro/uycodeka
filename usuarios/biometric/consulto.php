<?php
include "zklibrary.php";

$ip=$_POST['ip'];
$udp_port=$_POST['udp_port'];

$zk = new ZKLibrary($ip, $udp_port);
$zk->connect();
$zk->disableDevice();

$data = $zk->getPlatform($net = true).'*'.$zk->getFirmwareVersion($net = true).'*'.$zk->getSerialNumber($net = true).'*'.$zk->getDeviceName($net = true);
$zk->testVoice();
sleep(1);

$zk->enableDevice();
$zk->disconnect();
    echo json_encode($data);
    flush();          	

?>