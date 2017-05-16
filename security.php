<?php

/*
include in every secure page:
	<?php
	include("security.php");
	?>

	and add logout:
	<a href="logout.php">Log out</a>
*/
session_start();
 
$user["admin1"] = "password";
$user["admin2"] = "password";
$user["admin3"] = "password";
 
if (!isset($_SESSION['logged_in']))
{
    echo '<h1>Login</h1>';
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if (empty($_POST['username']) || empty($_POST['password']))
        {
            echo '<span style="color:red; font-weight: bold">Please fill in all fields!</span>';
        }
        elseif ($user[$_POST['username']] != $_POST['password'])
        {
            echo '<span style="color:red; font-weight: bold">Your username/password is wrong!</span>';
        }
        else
        {
            header("Refresh: 1");
            $_SESSION['ingelogd'] = true;
            echo '<span style="color:green; font-weight: bold">You are now logged in!</span>';
        }
    }
    else
    {
        exit('You need to log-in to view this page.<br /><br />
        <form method="POST" action=""><p>
        Username:<br />
        <input type="text" name="username" /><br /><br />
        Password:<br />
        <input type="password" name="password" /><br /><br />
        <input type="submit" value="Login" /> <input type="reset" value="Empty fields" />
        </form>');
    }
}
?>