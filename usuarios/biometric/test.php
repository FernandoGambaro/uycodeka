<?php
include "zklibrary.php";
$zk = new ZKLibrary('192.168.1.124', 4370);
if ($zk->ping()!="down"){
	echo "<br>up";	

$zk->setTimeout(60);

$zk->connect();
echo "<---<br>".$zk->disableDevice();
		//var_dump($zk->writeLCD(5, "En uso, aguarde por favor"));
echo $zk->getPlatform($net = true);
echo "<br>". $zk->getFirmwareVersion($net = true);
echo "<br>". $zk->getWorkCode($net = true);
//echo "<br>". $zk->getSSR();
echo "<br>". $zk->getPinWidth($net = true);
echo "<br>". $zk->getFaceFunctionOn($net = true);
echo "<br>". $zk->getSerialNumber($net = true);
echo "<br>". $zk->getDeviceName($net = true);
echo "<br>". $zk->getTime();
echo "<br>SSR".$zk->getSSR($net = true);
//var_dump($zk->getUserTemplate(7, 0));
//var_dump($zk->getAttendance());
echo "<br>Cantidad usuarios: ".$zk->countUser();
$users = $zk->getUser(7);
//$zk->testVoice();
//var_dump($zk->getUserData());
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
<thead>
  <tr>
    <td width="25">No</td>
    <td>UID</td>
    <td>ID</td>
    <td>Name</td>
    <td>Card</td>
    <td>Role</td>
    <td>Password</td>
  </tr>
</thead>

<tbody>
<?php

  var_dump($template=$zk->getUserTemplate(7, 0));
  var_dump($zk->testUserTemplate($uid, $finger));
  

  $template1_data = [
          'size' => $template[0],
          'pin' =>$template[1],
          'finger_id' =>$template[2],
          'valid' => $template[3],
          'template' => $template[4],
        ];
       //$template1_data=pack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6',$template1_data) ;
       var_dump($zk->setUserTemplate( $template[0],  $template[1], $template[2], $template[3], $template[4]));
     

$no = 0;
foreach($users as $key=>$user)
{
  $no++; 
?>
  <tr>
    <td align="right"><?php echo $no;?></td>
    <td><?php echo $key;?></td>
    <td><?php echo $user[0];?></td>
    <td><?php echo $user[1];?></td>
    <td><?php echo $user[2];?></td>
    <td><?php echo $user[3];?></td>
    <td><?php echo $user[4];?></td>
  </tr>

<?php
}
?>

</tbody>
</table>
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <th colspan="6">Data Attendance</th>
            </tr>
            <tr>
                <th>Index</th>
                <th>UID</th>
                <th>ID</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jam</th>
            </tr>
<?php

$zk->setUser(2, 2, "Fernando Gámbaro", "1234567", 0);
$attendance =$zk->getAttendance();
            sleep(1);
            while(list($idx, $attendancedata) = each($attendance)):

                ?>
                <tr><td><?php echo $attendancedata[2];?></td></tr>
                <?php
            
                if ( $attendancedata[2] == 2 )
                    $status = 'Check Out';
                else
                    $status = 'Check In';
            ?>
            <tr>
                <td><?php //echo $idx; ?></td>
                <td><?php echo $attendancedata[0] ?></td>
                <td><?php echo $attendancedata[1] ?></td>
                <td><?php echo $status ?></td>
                <td><?php echo date( "d/m/Y", strtotime( $attendancedata[3] ) ) ?></td>
                <td><?php echo date( "H:i:s", strtotime( $attendancedata[3] ) ) ?></td>
            </tr>
            <?php
            endwhile
            ?>
        </table>
        <?php
/*
$attendance = $zk->getAttendance();
$now = date('Y-m-d');
$jmldatamsk = 0;
$jmldataklr = 0;
$jmldl = 0;

    while (list($idx, $attendancedata) = each($attendance)) {
        $NIP = $attendancedata[1];
        $state = $attendancedata[2];
        $DATE = $attendancedata[3];
        $TIME = date("H:i:s", strtotime($attendancedata[3]));
        $ckTGL = date("Y-m-d", strtotime($DATE));
       
    }
    */
    //$zk->clearattendance();
    $zk->getTime();

var_dump($zk->enableDevice());
var_dump($zk->disconnect());
} else {
	echo "<br>Down";	
}
?>