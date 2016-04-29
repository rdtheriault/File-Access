<!DOCTYPE html>
<!--
Files accepted - 
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
<?php

if(isset($_POST['username']) && isset($_POST['password'])){

    $adServer = "ldap://server-dc-00.domain.local";//windows AD
	
    $ldap = ldap_connect($adServer);
    $username = $_POST['username'];
    $password = $_POST['password'];

    $ldaprdn = 'hsd' . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = ldap_bind($ldap, $ldaprdn, $password);


    if ($bind) {
        $filter="(sAMAccountName=$username)";
        $result = ldap_search($ldap,"ou=Domain,dc=Name,dc=local",$filter);
        ldap_sort($ldap,$result,"sn");
        $info = ldap_get_entries($ldap, $result);
        //for ($i=0; $i<$info["count"]; $i++)
        //{
        //    if($info['count'] > 1)
        //       break;
        //    echo "<p>You are accessing <strong> ". $info[$i]["sn"][0] .", " . $info[$i]["givenname"][0] ."</strong><br /> (" . $info[$i]["samaccountname"][0] .")</p>\n";
        //    echo '<pre>';
        //    var_dump($info);
        //    echo '</pre>';
        //    $userDn = $info[$i]["distinguishedname"][0]; 
        //}
        ldap_close($ldap);
        if ($info != '')
        {
            ldap_unbind($bind);
            session_start();
            $_SESSION['userName'] = $username;
            $_SESSION['IdoHaveAccess'] = "secretPass";//might want to change this, it is in other index.php as well
            header("Location: files/index.php");
            exit();
        }
        else {
            header("Location: index.php");
            exit();
        }
    } else {
        $msg = "Invalid email address / password";
        echo $msg;
        ldap_unbind($bind);
    }

}else{
?>
<br>Please enter the username you use for computer access (lastname first initial).<br> You are entering it one more time as double protection for your files. <br>
    <form action="#" method="POST">
        <label for="username">Username: </label><input id="username" type="text" name="username" /> 
        <label for="password">Password: </label><input id="password" type="password" name="password" />        <input type="submit" name="submit" value="Submit" />
    </form>
<?php } ?> 
        
    </body>
</html>
