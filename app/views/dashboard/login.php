		<h2>Login</h2>
		<form action="<?=current_url();?>" method="post">
			<ol>
				<li>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" value="<?=set_value('username');?>" />
					<?=form_error('username');?>
				</li>
				<li>
					<label for="password">Password</label>
					<input type="password" name="password" id="password" />
					<?=form_error('password');?>
				</li>
				<li>
					<input type="submit" value="Login" />
				</li>
			</ol>
		</form>