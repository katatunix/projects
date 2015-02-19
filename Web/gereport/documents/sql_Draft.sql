SELECT M.`id`
FROM `member` M, `memberproject` MP
WHERE
	M.`id` = MP.`memberId` AND
	MP.`projectId` = ? AND
	M.`id` NOT IN (
		SELECT A.`memberId` FROM `report` A
		WHERE A.`projectId` = ?
			AND A.`dateFor` = ?
	)
	