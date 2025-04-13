<?php
session_start();

if($_SERVER['REQUEST_METHOD']==='POST'){
$username=$_POST['username'];
$password=$_POST['password'];
$users=json_decode(file_get_contents("users.json"),true);


$found=false;

foreach($users as $user)
{
    if($user['username']=== $username && $user['password']===$password){
        $_SESSION['username'] = $username;
        $found=true;
        break;
    }
}
}


if($found){
    header("Location: index.html");
    exit();
}else{
    echo "Invalid username or password!";
}

?>