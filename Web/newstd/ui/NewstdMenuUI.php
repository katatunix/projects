<?php
	function getStyle($pageTitle, $title)
	{
		return $pageTitle == $title ? 'style="background-color: orange"' : '';
	}
?>

<div class="button" id="show">
	<a href="javascript:void()"><img src="<?= __UI_DIR_URL ?>/images/menu.gif" /></a>
</div>

<div class="clear-float"></div>
<div class="nav">
	<ul>
		<li><a href="<?= __SITE_CONTEXT ?>/newstd/logout">Logout</a></li>
		<li <?= getStyle($pageTitle, 'FAQ') ?>>	<a href="<?= __SITE_CONTEXT ?>/newstd/faq">FAQ</a></li>

		<li><a><b>INITIAL ACTIONS</b></a></li>
		<li <?= getStyle($pageTitle, 'Pre-enrolment') ?>>	<a href="<?= __SITE_CONTEXT ?>/newstd/preEnrolment">Pre-enrolment</a></li>
		<li <?= getStyle($pageTitle, 'Enrolment') ?>>		<a href="<?= __SITE_CONTEXT ?>/newstd/enrolment">Enrolment</a></li>
		<li <?= getStyle($pageTitle, 'Pay Your Fees') ?>>	<a href="<?= __SITE_CONTEXT ?>/newstd/payYourFees">Pay Your Fees</a></li>
		<li <?= getStyle($pageTitle, 'Accommodation') ?>>	<a href="<?= __SITE_CONTEXT ?>/newstd/accommodation">Accommodation</a></li>

		<li><a><b>NEXT STEPS</b></a></li>
		<li <?= getStyle($pageTitle, 'Get Your ID') ?>>		<a href="<?= __SITE_CONTEXT ?>/newstd/getYourID">Get Your ID</a></li>
		<li <?= getStyle($pageTitle, 'Get Connected') ?>>	<a href="<?= __SITE_CONTEXT ?>/newstd/getConnected">Get Connected</a></li>

		<li><a><b>FINISH</b></a></li>

	</ul>
	<div id="hide">
		<a href="#">&laquo; hide menu &raquo;</a>
	</div>
</div>

<script type="text/javascript">
	$('.nav').hide();
	var g_navShow = false;
	$('#show').click(function () {
		if (g_navShow)
			$('.nav').hide();
		else
			$('.nav').show();
		g_navShow = !g_navShow;
	});
	$('#hide').click(function () {
		$('.nav').hide();
		g_navShow = false;
	});
</script>
