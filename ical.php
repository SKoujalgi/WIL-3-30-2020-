<?php 

  $date      = gmdate($_GET['date']); echo $date;
  $startTime = gmdate($_GET['startTime']); echo ($startTime);
  $endTime   = $_GET['endTime'];echo ($endTime);
  $subject   = $_GET['subject'];echo $subject;
  $desc      = $_GET['desc'];echo $desc;

/*
$date='20200320';
$startTime = '1300';
$subject   = 'Changed - This is a test calcendar event';
$desc      = 'Changed - Its a fun party';
$endTime   = '1400';
*/

/*
  $ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Exultancy/WIL//v1.0//EN
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "example.com
DTSTAMP:"  .$date."
DTSTART:".$startTime."
SUMMARY:".$subject."
DESCRIPTION:".$desc."
END:VEVENT
END:VCALENDAR";

DTSTAMP:" .$date.'T'. gmdate('His') . "Z

  //set correct content-type-header
  header('Content-type: text/calendar; charset=utf-8');
  header('Content-Disposition: inline; filename=calendar.ics');
 */

$ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "example.com
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:".$date."T".$startTime."00Z
DTEND:".$date."T".$endTime."00Z
SUMMARY:".$subject."
DESCRIPTION:".$desc."
END:VEVENT
END:VCALENDAR";

 //set correct content-type-header
 header('Content-type: text/calendar; charset=utf-8');
 header('Content-Disposition: inline; filename=calendar.ics');
 echo $ical;
 exit;
?>