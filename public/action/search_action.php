<?php
$key=$_POST['keyword'];
for ($i=0; $i <$key ; $i++) {
	# code...
	
?>
<li class="unread available">
	<a href="javascript:;">
		<div class="notice-icon">
			<i class="fa fa-check"></i>
		</div>
		<div>
			<span class="name">
				<strong>Server needs to reboot</strong>
				<span class="time small">15 mins ago</span>
			</span>
		</div>
	</a>
</li>
<?php } ?>
