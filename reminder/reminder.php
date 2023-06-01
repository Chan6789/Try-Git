<?php

$server = "localhost";
$user = "root";
$pass = "";
$data = "reminder";

$conn = mysqli_connect($server,$user,$pass,$data);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}  

if(isset($_POST["sub"])){
    $time = $_POST['task_time'];
    $mysql_datetime = date('Y-m-d H:i:s', strtotime($time));

    $insert="INSERT INTO task(`task_name`,`reminder_datetime`,`task_description`) values('$_POST[t_name]','$mysql_datetime','$_POST[t_description]')";
    if(mysqli_query($conn,$insert)){
        echo "<script>
            alert('Task setup success');
        </script>";
    }else{
        echo "<script>
            alert('Task setup fail');
        </script>";
    }
}

if(isset($_POST["cancel"])){
    $qry=mysqli_query($conn,"DELETE FROM task where id='$_POST[cancel]'");
}


$qry="SELECT *,DATEDIFF(`reminder_datetime`,'".date("Y-m-d")."') as day FROM `task` where DATE(reminder_datetime)>='".date("Y-m-d")."' ORDER BY reminder_datetime asc";
$sttr=mysqli_query($conn,$qry);





?>

<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<style>
    #customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
fieldset{
    width: 30%;
    
}
.ta{
   width: 300px;
   height: 150px;
}
@keyframes yellow-alert {
        from {background-color: yellow;}
        to {background-color: #fff;}
        
        
    } 
    .r-yellow{
        animation-name: yellow-alert;
        animation-duration:1s;
        animation-iteration-count: infinite;
    }
    @keyframes red-alert {
        from {background-color: red;}
        to {background-color: #fff;}
        
        
    } 
    .r-red{
        animation-name:  red-alert;
        animation-duration:1s;
        animation-iteration-count: infinite;
    }
</style>

<body>
<div id="clock">
</div>
<div class="container">
    <form action="" method="post">
        <fieldset>
            <legend>Setup the task remind</legend>
            <div>
                <label>Task name</label><br>
                <input type="text" name="t_name" value="" /><br>
                <label>Task Description</label><br>
                <textarea class="ta" name="t_description"></textarea><br>
                <label>Date remind</label><br>
                <input type="datetime-local" id="t_time" name="task_time"><br><br>
                <button type="submit" name="sub"  >Submit</button>
            </div>
        </fieldset>
    </form>
    <br>
    <div>
        <form action="" method="POST">
        <table id="customers">
            <thead>
                <th></th>
                <th>Task Name</th>
                <th>Task Description</th>
                <th>Remind date</th>
                <th colspan="2">action</th>
            </thead>
            <tbody>
                <tr>
                <?php 
                $class="";
                    while($row=mysqli_fetch_array($sttr)){
                    $daydiff=$row["day"];

                   
                    if($daydiff<=7 && $daydiff>0){
                        echo "<td class='r-yellow'>Warning your task will apprear in $daydiff day</td>";
                        
                    }elseif($daydiff==0){
                        echo "<td class='r-red'>the task expired date is today</td>";
                        
                    }else{
                        echo "<td>$daydiff days left</td>";
                    }
                    
                    
                ?>
                    <p id="demo">
                    <input type="hidden" id="date" value="<?=$row["reminder_datetime"]?>">
                    </p>
                        <td><?=$row["task_name"]?></td>
                        <td><?=$row["task_description"]?></td>
                        <td><?=$row["reminder_datetime"]?></td>
                        <!-- <td><button type="button" onclick="window.location.href='edit.php?id=<?=$row['id']?>'" name="edit">modify</button></td> -->
                        <td><button type="submit" value="<?=$row["id"]?>" name="cancel">Cancel</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </form>

</div>
<script>

// Set the date we're counting down to

var date = document.getElementById("date").value;

var countDownDate = new Date(date).getTime();
console.log(countDownDate);
// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    alert("Expired")
  }
}, 1000);


</script>

</body>
</html>