<ul>
	<?php
	foreach ($this->projects as $project)
	{
	?>
		<li><a href="<?= $this->urlSource->getReportUrl() ?>
			?p=<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></a>
		</li>
	<?php
	}
	?>
</ul>
