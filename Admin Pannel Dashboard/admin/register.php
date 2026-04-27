<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
        <style>

            body{
                height : 100%;
                width : 100%;
                display :flex;
                flex-direction:column;
                align-items:center;
                justify-content:center;
            }
            form{
                margin-top:10px;
                margin-bottom:10px;
                width:50%;
                border:1px solid white;
                border-radius:10px;
                background-color:rgba(256,256,180,0.2);
                display :flex;
                flex-direction:column;
                align-items:center;
                justify-content:center;
                gap:10px;
                padding:12px 15px;
                color:black;
                box-shadow:0 0 15px black ;
            }
            input{
                width:85%;
                padding:15px 20px;
                border-radius:5px;
            }
            button{
                padding:15px 25px;
                border-radius:10px;
                border:white;
                background-color : gray;
                color:white;
                transition:all 100ms;
            }
            button:hover{
                transform:scale(1.05);
            }
            button:active{
                transform:scale(0.93);
            }
            #radioBox{
                display:flex;
                gap:20px;
            }
        </style>
</head>
<body>
    <form  action="" method="post">
        <h1><b><i>Registration Form</i></b></h1>
        <input type="text" name="name1" id="" placeholder = "First Name">
        <input type="text" name="name2" id="" placeholder = "Last Name">
        <label for="">Select your gender</label>
        <div id = "radioBox">
            <input type="radio" name ="gender" value="Male">Male
            <input type="radio" name ="gender" value="Female">Female
            <input type="radio" name ="gender" value="Other">Other
        </div>
        <input type="number" name="contact" id="" placeholder = "Contact">
        <input type="email" name="email" id="" placeholder = "Email">
        <input type="password" name="password" id="" placeholder = "Password">
        <input type="text" name="address" id="" placeholder = "Address">
        <button type="submit" name="MyForm">Submit</button>
    </form>
    <?php
    session_start();
    $_SESSION["signed"] = "";
    
if(isset($_POST['MyForm'])){
    if(empty($_POST["name1"]) || empty($_POST["name2"]) || empty($_POST["contact"]) || empty($_POST["email"]) || empty($_POST["gender"]) || empty($_POST["password"]) || empty($_POST["address"]))
        {
            echo "<h2>All fields are required !</h3>";
        }
        else {
            $_SESSION["name1"] = $_POST["name1"];
            $_SESSION["name2"] = $_POST["name2"];
            $_SESSION["gender"] = $_POST["gender"];
            $_SESSION["contact"] = $_POST["contact"];
            $_SESSION["address"] = $_POST["address"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["password"] = $_POST["password"];

            echo "<h3><b>Welcome for making an Account !</b></h3>";
            header("location:signup.php");
        }
}
    
    ?>
</body>
</html>