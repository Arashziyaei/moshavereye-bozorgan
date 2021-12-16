<?php
$names_array=array();
$messages_array=array();
$messages=fopen("messages.txt", "r");
$i=0;
while(!feof($messages)){
    $messages_array[$i]= fgets($messages);
    $i++;
}
$flag=file_get_contents('people.json');
$i=1;
$marg=json_decode($flag);
foreach($marg as $key => $value){
    $names_array[$i]=$key;
    $i++;
}
if($_SERVER["REQUEST_METHOD"]== "POST"){
    $en_name = $_POST['person'];
    foreach($marg as $key => $value){
        if($key==$en_name){
            $fa_name=$value;
            break;
        }
    }
    $question=$_POST['question'];
    $random1=hash('adler32',$question." ".$en_name);
    $random1=hexdec($random1);
    $C_messages=16;
    $random_find_message=($random1 % $C_messages);
    $msg=$messages_array[$random_find_message];
    $startEmessage = "/^آیا/iu";
    $end = "/\?$/i";
    $end1 = "/؟$/u";
    if(!preg_match($startEmessage,$question)){
        $msg="سوال درستی پرسیده نشده";
    }
    if(!(preg_match($end,$question)||preg_match($end1,$question))){
        $msg="سوال درستی پرسیده نشده";
    }
}
else{
    $msg="سوال خود را بپرس!";
    $question='';
    $random2=array_rand($names_array);
    $en_name=$names_array[$random2];
    foreach($marg as $key => $value){
        if($key==$en_name){
            $fa_name=$value;
            break;
        }
    }
}
if(isset($question)){
    $j='پرسش:';
}
if(empty($question)){
    $j='';
    $msg="سوال خود را بپرس!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">پرسش:</span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                    $flag=file_get_contents('people.json');
                    $list_en_name=json_decode($flag);
                       foreach($list_en_name as $key => $value){
                           if($key==$en_name){
                               echo "<option value=$key selected> $value </option>";
                           }
                           else{
                               echo "<option value=$key> $value </option>";
                           }
                       }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>
