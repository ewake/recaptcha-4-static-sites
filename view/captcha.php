<?php
$data['title'] = 'Help us to fight SPAM!';
?>

<form action="" method="post">
    <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha.public_key'] ?>"></div>
    
    <br>
    
	<button type="submit" class="btn btn-primary btn-lg">Confirm &nbsp; <span class="glyphicon glyphicon-send" aria-hidden="true"></span></button>
</form>

<script src="//www.google.com/recaptcha/api.js"></script>
