<?php 
$config = require __DIR__.'/config.php';

session_start();

$data = $errors = array();
$content = 'home';

if(!isset($_SESSION['request_time'])) 
	$_SESSION['request_time'] = $_SERVER['REQUEST_TIME'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	if(isset($_POST['g-recaptcha-response'])) {

		if(isset($_SESSION['fields'])) {
			
			$content = 'captcha';
			
			if(!empty($_POST['g-recaptcha-response'])) {
			
				$response_json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$config['recaptcha.private_key'].'&response='.$_POST['g-recaptcha-response']);
				$response = json_decode($response_json);
			
				if($response->success) {
				
					$subject = 'Email from '.getenv('HTTP_HOST');
					$subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
					$encoded_subject = "=?UTF-8?B?".base64_encode($subject)."?=\n";
			
					$headers = 'MIME-Version: 1.0'.PHP_EOL;
					$headers .= 'Content-Transfer-Encoding: 8bit'.PHP_EOL;
					$headers .= 'Content-Type: text/html; charset="UTF-8"'.PHP_EOL;
					//$headers .= 'From: '.mb_encode_mimeheader($_SESSION['fields']['name'], 'UTF-8').' <'.$_SESSION['fields']['email'].'>'.PHP_EOL; // DKIM & DMARC are ok?
					$headers .= 'From: '.$config['email.from'].PHP_EOL; // DKIM & DMARC pass
					$headers .= 'X-Mailer: PHP/'.phpversion();
				
					$message = strtr(file_get_contents(__DIR__.'/view/email/contact.tpl'), array(
						'{{name}}' => $_SESSION['fields']['name'],
						'{{email}}' => $_SESSION['fields']['email'],
						'{{message}}' => nl2br($_SESSION['fields']['message']),
					));
	
					if(@mail(trim($config['email.to']), $encoded_subject, $message, $headers)) {

						$_SESSION['fields'] = null;
						unset($_SESSION['fields']);

						header('Location: thanks.html');
						exit();
				
					} else {			
						$errors[] = 'There was a technical problem, please try again later.';
					}	
				
				} else {
					$errors[] = 'reCAPTCHA incorrect, please try again.';
				}
			
			} else {
				$errors[] = 'You must check the box of reCAPTCHA.';
			}
			
		} else {
			$errors[] = 'You must fill in all mandatory fields.';
		}
		
	} else {

		if(!isset($_SESSION['REQUEST_COUNTER'])) 
			$_SESSION['REQUEST_COUNTER'] = 0;
		$_SESSION['REQUEST_COUNTER']++;
	
		$postdata = $_POST;
		array_walk_recursive($postdata, function(&$value){
			$value = trim($value);
			$value = stripslashes($value);
		});
	
		extract($postdata);

		if(!empty($postdata['honeypot']) || isset($_SESSION['BLOCKED_IP'])) {
			$errors[] = 'Attempted SPAM detected';
			$_SESSION['BLOCKED_IP'] = getenv('REMOTE_ADDR');
		}

		$diff = $_SERVER['REQUEST_TIME'] - $_SESSION['request_time'];
		$_SESSION['request_time'] = $_SERVER['REQUEST_TIME'];
	
		if($_SESSION['REQUEST_COUNTER'] == 1 && $diff <= 2)
			$errors[] = 'Attempted SPAM detected: wait a few seconds and try again...';
	
		if(empty($name))
			$errors[] = 'Name field blank or incorrect';
	
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			$errors[] = 'The Email does not seem valid';
	
		if(empty($message))
			$errors[] = 'Message  field blank or incorrect';
			
		if(sizeof($errors) == 0) {
			
			$_SESSION['fields'] = array(
				'name' => $name,
				'email' => $email,
				'message' => $message,
			);	
			
			$content = 'captcha';
		}
	}
	
} else {
	$errors[] = 'You must fill out the contact form.';
}

ob_start();
require __DIR__.'/view/'.$content.'.php';
$data['content'] = ob_get_contents();
ob_end_clean();

ob_start();
require __DIR__.'/view/layout/master.php';
$layout = ob_get_contents();
ob_end_clean();

$data = array_merge(array(
	'lang' => 'en',
	'meta_title' => 'Brand',
	'title' => '',
	'sub_title' => '',
	'content' => '',
), $data);

$output = strtr($layout, array(
	'{{lang}}' => $data['lang'],
	'{{meta_title}}' => $data['meta_title'],
	'{{title}}' => $data['title'],
	'{{sub_title}}' => $data['sub_title'],
	'{{content}}' => $data['content'],
));
					
echo $output;
