<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ICS timezone editor</title>
</head>

<body>

<?php 
if ($_GET[feed]):
	$feed = preg_replace('/^.*:\/\//', 'http://', $_GET[feed]);
	echo '
	Use this new feed URL in your calendar program such as Google Calendar:<br /><br />

	http://'.$_SERVER['SERVER_NAME'].'/ics.php?timezone=' . urlencode($_GET[timezone]) . '&feed=' . urlencode($feed) . '<br /><br />
	
	Did it work? Kindly have a look at my <a href="http://www.kentwang.com">menswear store</a><br />
	Problems? Email <a href="mailto:kwang@kwang.org">kwang@kwang.org</a>.<br />
I promise I do not store your private data, and will gladly provide the source code so you can run the script on your server.'; 

else:

function get_timezones() 
{ 
    if (method_exists('DateTimeZone','listIdentifiers')) 
    { 
        $timezones = array(); 
        $timezone_identifiers = DateTimeZone::listIdentifiers(); 
  
        foreach( $timezone_identifiers as $value ) 
        { 
            if ( preg_match( '/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $value ) ) 
            { 
                $ex=explode('/',$value);//obtain continent,city 
                $city = isset($ex[2])? $ex[1].' - '.$ex[2]:$ex[1];//in case a timezone has more than one 
                $timezones[$ex[0]][$value] = $city; 
            } 
        } 
        return $timezones; 
    } 
    else//old php version 
    { 
        return FALSE; 
    } 
} 
  
function get_select_timezones($select_name='timezone',$selected=NULL) 
{ 
    $timezones = get_timezones(); 
    $sel.='<select id="'.$select_name.'" name="'.$select_name.'">'; 
    foreach( $timezones as $continent=>$timezone ) 
    { 
        $sel.= '<optgroup label="'.$continent.'">'; 
        foreach( $timezone as $city=>$cityname ) 
        {            
            if ($selected==$city) 
            { 
                $sel.= '<option selected=selected value="'.$city.'">'.$cityname.'</option>'; 
            } 
            else $sel.= '<option value="'.$city.'">'.$cityname.'</option>'; 
        } 
        $sel.= '</optgroup>'; 
    } 
    $sel.='</select>'; 
  
    return $sel; 
} 
?>

<h1>ICS timezone editor</h1>

This script will add the specified timezone to your ICS calendar feed.<br /><br />In particular, this addresses the problem of the TripIt calendar feed not setting the timezone. When imported to Google Calendar, it assumes it is in the UTC (GMT) timezone and adjusts the times unnecessarily.<br /><br />
In TripIt, uncheck "Automatically adjust time zones in your calendar feed". In Google Calendar, go to Settings and make sure the timezone is correct.<br /><br />

<form action="ics-timezone.php" method="get">

Timezone: <?php echo get_select_timezones() ?><br />
ICS feed URL: <input type="text" name="feed" size="50" /><br />
<input type="submit" value="Get new feed" />

</form>
<?php 
endif;
?>
</body>
</html>
