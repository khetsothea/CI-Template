		<h2>User registration</h2>
		<form action="<?=current_url();?>" method="post">
			<ol>
				<li>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" value="<?=set_value('username');?>" />
					<?=form_error('username');?>
				</li>
				<li>
					<label for="email">Email</label>
					<input type="email" name="email" id="email" value="<?=set_value('email');?>" />
					<?=form_error('email');?>
				</li>
				<li>
					<label for="password">Password</label>
					<input type="password" name="password" id="password" />
					<?=form_error('password');?>
				</li>
				<li>
					<label for="conf_password">Confirm</label>
					<input type="password" name="conf_password" id="conf_password" value="<?=set_value('conf_password');?>" />
					<?=form_error('conf_password');?>
				</li>
				<li>
					<input type="submit" value="Register" />
				</li>
			</ol>
		</form>