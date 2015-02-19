<div id="about">
	<h2>Hello,
		<?
			if ( $cm = MySession::currentMember() )
			{
				echo $cm->username();
			}
			else
			{
				echo 'guest';
			}
		?>
	</h2>
</div>
