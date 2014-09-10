<?php
$email_to = "andrew@yellowmuttbrewery.com";
$email_subject = "Message from User";
function died($error) {
	echo "<script>";
	echo "alert('We are very sorry, but there were error(s):\\n\\n$error \\nPlease go back and fix these errors.');";
	echo "window.location.replace(\"../contact.php\");";
	echo "</script>";
	die ();
}

$name = $_POST ['name'];

$email_from = $_POST ['email'];

$subject = $_POST ['subject'];

$message = $_POST ['message'];

$error_message = "";

$string_exp = "/^[A-Za-z .'-]+$/";

if (! preg_match ( $string_exp, $name )) {
	
	$error_message .= '-The name you entered does not appear to be valid.\n';
}

$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

if (! preg_match ( $email_exp, $email_from )) {
	
	$error_message .= '-The Email Address you entered does not appear to be valid.\n';
}

if ($subject == "") {
	$error_message .= '-You must include a subject.\n';
}

if ($message == "") {
	$error_message .= '-You must submit a message.\n';
}

if (strlen ( $error_message ) > 0) {
	
	died ( $error_message );
}

$email_message = "Form details below.\n\n";
function clean_string($string) {
	$bad = array (
			"content-type",
			"bcc:",
			"to:",
			"cc:",
			"href" 
	);
	
	return str_replace ( $bad, "", $string );
}

$email_message .= "Name: " . clean_string ( $name ) . "\n";

$email_message .= "Email: " . clean_string ( $email_from ) . "\n";

$email_message .= "Message: " . clean_string ( $message ) . "\n";

mail ( $email_to, $email_subject, $email_message );

?>
<script>
alert('Thank you for contacting us. We will be in touch with you very soon.');
window.location.replace("../contact.php");
</script>