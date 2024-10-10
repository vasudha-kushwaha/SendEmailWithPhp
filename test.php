<?php
include('smtp/PHPMailerAutoload.php');

// Event details
$event = [
    'start' => '2024-10-10 16:16:00',
    'end' => '2024-10-10 16:18:00',
    'summary' => 'Project Kickoff Meeting',
    'description' => 'Discuss project scope, milestones, and deliverables.',
    'location' => '123 Business Ave, Suite 100, City, Country',
    'organizer_name' => 'John Doe',
    'organizer_email' => 'john.doe@yourdomain.com',
    'attendee_name' => 'Jane Smith',
    'attendee_email' => 'jane.smith@recipientdomain.com'
];

// Function to create the iCalendar (.ics) content
function createICS($event) {
    $uid = uniqid() . '@yourdomain.com';
    $dtStart = gmdate('Ymd\THis\Z', strtotime($event['start']));
    $dtEnd = gmdate('Ymd\THis\Z', strtotime($event['end']));
    $dtStamp = gmdate('Ymd\THis\Z');

    $ics = "BEGIN:VCALENDAR\r\n";
    $ics .= "VERSION:2.0\r\n";
    $ics .= "PRODID:-//Your Company//Your Product//EN\r\n";
    $ics .= "CALSCALE:GREGORIAN\r\n";
    $ics .= "METHOD:REQUEST\r\n";
    $ics .= "BEGIN:VEVENT\r\n";
    $ics .= "UID:" . $uid . "\r\n";
    $ics .= "DTSTAMP:" . $dtStamp . "\r\n";
    $ics .= "DTSTART:" . $dtStart . "\r\n";
    $ics .= "DTEND:" . $dtEnd . "\r\n";
    $ics .= "SUMMARY:" . addslashes($event['summary']) . "\r\n";
    $ics .= "DESCRIPTION:" . addslashes($event['description']) . "\r\n";
    $ics .= "LOCATION:" . addslashes($event['location']) . "\r\n";
    $ics .= "ORGANIZER;CN=" . addslashes($event['organizer_name']) . ":MAILTO:" . $event['organizer_email'] . "\r\n";
    $ics .= "ATTENDEE;CN=" . addslashes($event['attendee_name']) . ";RSVP=TRUE:MAILTO:" . $event['attendee_email'] . "\r\n";
    $ics .= "END:VEVENT\r\n";
    $ics .= "END:VCALENDAR\r\n";

    return $ics;
}

$icsContent = createICS($event);


echo smtp_mailer('vasudha.kush@gmail.com','Subject','Hello Vk', $icsContent);
function smtp_mailer($to,$subject, $msg, $icsContent=null){
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'tls'; 
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	//$mail->SMTPDebug = 2; 
	$mail->Username = "vasudha.kush@gmail.com";
	$mail->Password = "zbtmpipstqvwpfrl";
	$mail->SetFrom("email");
	$mail->Subject = $subject;
	$mail->Body =$msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	// Attach the .ics file if $icsContent is not null
	if ($icsContent != null)
    	$mail->addStringAttachment($icsContent, 'invite.ics', 'base64', 'text/calendar; method=REQUEST');
	
	if(!$mail->Send()){
		echo $mail->ErrorInfo;
	}else{
		return 'Sent';
	}
}
?>