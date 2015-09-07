<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="keywords" content="RaspberryPi,Media,Decode,HDMI">
<title>Raspberry Pi Project</title>
</head>

<body>

<?php     

$ip_address = test_input(@$_POST["ip_address"]);
$netmask = test_input(@$_POST["netmask"]);
$gateway = test_input(@$_POST["gateway"]);
$multicast_group = test_input(@$_POST["multicast_group"]);
$multicast_port = test_input(@$_POST["multicast_port"]);
$ip_error = $netmask_error = $gateway_error = $group_error = $port_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply']))  {
  if (empty($_POST["ip_address"]) || empty($_POST["netmask"]) || empty($_POST["gateway"])){
    if (empty($_POST["ip_address"]))
      $ip_error = "Can not be empty!";
    if (empty($_POST["netmask"]))
      $netmask_error = "Can not be empty!";
    if (empty($_POST["gateway"]))
      $gateway_error = "Can not be empty!";
  }
  else {
    if ((!is_ipv4address($ip_address)) || (!is_netmask($netmask)) || (!is_ipv4address($gateway))){
        if (!is_ipv4address($ip_address)) 
          $ip_error = "Not a valid value!";
        if(!is_netmask($netmask)) 
          $netmask_error = "Not a valid value!";
        if(!is_ipv4address($gateway)) 
          $gateway_error = "Not a valid value!";
    }
    else {
      $ip_error = "✓";
      $netmask_error = "✓";
      $gateway_error = "✓";
      // echo "<script language=\"JavaScript\">alert(\"Apply Success!\");</script>";
      //echo "$ip_address test ";
      //echo <br>
     #echo shell_exec("sudo bash /usr/local/dsr/bin/config_ip.sh $ip_address $netmask $gateway");
     #$result = shell_exec("sudo bash /usr/local/dsr/bin/config_ip.sh $ip_address $netmask $gateway");
     #echo $result; 
      $aug_status = shell_exec("ps aux | grep augtool | awk '{ print $3 }' | sort -r | head -n 1");
      if($aug_status>0)
        echo "<script language=\"JavaScript\">alert(\"augtool is running now,can not repeat apply!\");</script>";
      else
        echo "<script language=\"JavaScript\">alert(\"Applying......\");</script>"; 
        echo shell_exec("sudo bash /usr/local/dsr/bin/config_ip.sh $ip_address $netmask $gateway");
    }
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start']))  {
  if (empty($_POST["multicast_group"]) || empty($_POST["multicast_port"])){
    if(empty($_POST["multicast_group"]))
      $group_error = "Can not be empty!";
    if (empty($_POST["multicast_port"]))
      $port_error = "Can not be empty!";
  }
  else {
    if((!is_castgroup($multicast_group)) || (!is_castport($multicast_port))){
      if(!is_castgroup($multicast_group))
        $group_error = "Not a valid value!";
      if(!is_castport($multicast_port))
        $port_error = "Not a valid value!";
    }
    else {
      $group_error = "✓";
      $port_error = "✓";
      echo "<script language=\"JavaScript\">alert(\"Start Multicast!\");</script>"; 
    }
  }
}

function is_ipv4address($ip){
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
      return true;
    else
      return false;
}

function is_netmask($ip){
   if (preg_match('/^(255.)((255|0).){2}(0)$/',$ip)) 
      return true;
   else
      return false;
}

function is_castgroup($ip){
   if (preg_match('/^((22[4-9]|23[0-9]).)(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){2}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$ip)) 
      return true;
   else
      return false;
}

function is_castport($ip){
  if(filter_var($ip,FILTER_VALIDATE_INT,array("options"=>array("min_range"=>1,"max_range"=>65535))))
     return true;
   else
     return false;
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

?>

<div id="container">
 
 <div id="con_div">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
      
      <h1 >IP Configuration</h1>
      <div id="ip_div">
        <table id="itable">
        <tr><td id="mtr">IP Address: </td><td><input type="text" id="ip_address" name="ip_address" 
          value="<?php echo $_POST["ip_address"]; ?>" ></td>
          <td><span class="error">*<?php echo $ip_error;?></span></td></tr>
        <tr><td>Netmast: </td><td><input type="text" id="netmask" name="netmask"
          value="<?php echo $_POST["netmask"]; ?>" ></td>
          <td><span class="error">*<?php echo $netmask_error;?></span></td></tr>
        <tr><td>Gateway: </td><td><input type="text" id="gateway" name="gateway"
          value="<?php echo $_POST["gateway"]; ?>" ></td>
          <td><span class="error">*<?php echo $gateway_error;?></span></td></tr>
        <!-- <tr><td></td><td><input type="submit" value="Apply" id="apply" name="apply"></td><td></td></tr> -->
        </table>
        <input type="submit" value="Apply" id="apply" name="apply">
      </div>
      
      <h1 >Multicast Stream Configuration</h1>
      <div id="cast_div">
        <table id="mtable">
        <tr><td>Multicast Group: </td><td><input type="text" id="multicast_group" name="multicast_group" 
          value="<?php echo $_POST['multicast_group']; ?>"></td>
          <td><span class="error">*<?php echo $group_error;?></span></td></tr>
        <tr><td>Multicast Port:</td><td><input type="text" id="multicast_port" name="multicast_port" 
          value="<?php echo $_POST['multicast_port']; ?>"></td>
          <td><span class="error">*<?php echo $port_error;?></span></td></tr>
        <!-- <tr><td></td><td><input type="submit" value="Start" id="start" name="start"></td><td></td></tr> -->
        </table>
        <input type="submit" value="Start" id="start" name="start">
      </div>
     
    </form>
 </div>

</div>

</body>
</html>



<style type="text/css">

#container{
  width:800px;
  margin:0 auto;
}

.error {
  color: #FF0000;
  font-size: 13px;
}

#con_div{
  width: 720px;
  height: 620px;
  background: #EEE;
  margin: 10px;
  border:#929292 10px solid; 
}

#con_div h1{
  color:#256757;
  font-family:'Microsoft Yahei', SimHei, sans-serif;
  font-size: 42px;
  text-align: center;
  margin:45px 0 35px 0;
}

#con_div input{
  width:175px;
}

#ip_div{
  margin-left: 200px;
}

#ip_div #apply{
  width:100px;
  height:28px;
  margin:12px 0 25px 112px;
  color:#FFFFFF;
  background:#45B46A;
}

#cast_div{
  margin-left: 180px;
}

#cast_div #start{
  width:100px;
  height:29px;
  margin:12px 0 0 132px;
  color:#FFFFFF;
  background:#45B46A;
}

</style>
