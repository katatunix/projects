<h1><a href="<?= $this->urlSource->getIndexUrl() ?>">GE Report</a></h1>
<h2>I love the way that you report</h2>

<div id="menu">
	<?php if ($this->username) { ?>
		<span id="hello">Hello, <?= htmlspecialchars($this->username) ?></span>
	<?php } ?>

	<a href="<?= $this->urlSource->getIndexUrl() ?>">Home</a> |
	<?php if ($this->username) { ?>
		<a href="<?= $this->urlSource->getOptionsUrl() ?>">Options</a> |
		<a href="<?= $this->urlSource->getLogoutUrl() ?>">Logout</a>
	<?php } else { ?>
		<a href="<?= $this->urlSource->getLoginUrl() ?>">Login</a>
	<?php } ?>
</div>
