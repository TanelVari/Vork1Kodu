<div id="login">
	<form action="?page=login" method="POST" >
		<input type="text" name="username" placeholder="nimi"/><br/>
		<input type="password" name="password" placeholder="parool"/><br/>
		<input type="submit" value="sisenen" name="login" />
	</form>
	<?php if (!empty($errors)):?>
		<?php foreach($errors as $error):?>
			<div class="error_text"><?php echo htmlspecialchars($error); ?></div>
		<?php endforeach;?>
	<?php endif;?>
    <?php

    ?>
</div>