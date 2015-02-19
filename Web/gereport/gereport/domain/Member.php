<?php

namespace gereport\domain;

interface Member
{
	const ADMIN	= 0;
	const MOD	= 1;
	const USER	= 2;

	public function getUsername();

	public function hasPassword($password);

	public function changePassword($newPassword);

	/**
	 * @param Project $project
	 */
	public function joinProject($project);

	/**
	 * @param int $projectId
	 * @return bool
	 */
	public function isWorkingForProject($projectId);

	/**
	 * @param Report $report
	 * @return bool
	 */
	public function canDeleteReport($report);
}
