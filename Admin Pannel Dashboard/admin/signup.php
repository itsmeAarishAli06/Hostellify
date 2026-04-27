<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>

         html,body{
                height : 100%;
                width : 100%;
                display :flex;
                flex-direction:column;
                align-items:center;
                justify-content:center;
            }
            form{
                margin-top:5px;
                width:50%;
                border:1px solid black;
                border-radius:10px;
                background-color:rgba(256,256,180,0.2);
                display :flex;
                flex-direction:column;
                align-items:center;
                justify-content:center;
                gap:10px;
                padding:15px 10px;
                color:black;
                box-shadow:0 0 15px black ;
            }
            input{
                width:90%;
                padding:15px 20px;
                border-radius:5px;
            }
            button{
                padding:15px 25px;
                border-radius:10px;
                transition:all 100ms;
            }
            button:hover{
                transform:scale(1.05);
            }
            button:active{
                transform:scale(0.93);
            }
        </style>
</head>
<body>
    
    <form action="" method="post">
        <h1><i><b>Sign up Form</b></i></h1>
        <input type="email" name="email" id="" placeholder = "Email">
        <input type="password" name="password" id="" placeholder = "Password">
        <button type="submit" name="btn">Submit</button>
    </form>
    <?php

    if (isset($_POST["btn"])){
        if(empty($_POST["email"]) || empty($_POST["password"])){
            echo "<h2>All fields are required !</h2>";
        }
        else {
            session_start();
            if ($_SESSION["email"] == $_POST["email"] && $_SESSION["password"] == $_POST["password"]) {
                $_SESSION["signed"] = "signed";
                echo "Thanks for signing you in the account!";
                header("location:index.php");
            }
            else if($_SESSION["email"] == $_POST["email"]){
                echo "<h2>Invalid Password !</h2>";
            }
            else{
            echo "<h2>No Account found !</h2>";
            }  
        }
        
    }   
    
    ?>
</body>
</html>