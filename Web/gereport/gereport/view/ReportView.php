<?php

namespace gereport\view;

__import('view/View');

class ReportView extends View
{
	private $projectId;
	private $date;

	private $addReportContent;
	private $isAddReportSuccess;
	private $addReportResultMessage;

	private $isAllowAddReport;

	private $reportIdToDelete;
	private $isDeleteReportSuccess;
	private $deleteReportResultMessage;

	private $reports;
	private $notReportedMembers;

	public function __construct($request, $urlSource, $htmlDir)
	{
		parent::__construct($request, $urlSource, $htmlDir);

		$this->projectId = $this->request->getData('p');
		$this->date = $this->request->getData('d');

		$this->addReportContent = $this->isPostMethod() ? $this->request->getDataPost('reportContent') : '';
		$this->isAddReportSuccess = false;
		$this->addReportResultMessage = '';
		$this->isAllowAddReport = false;
		$this->reportIdToDelete = $this->isPostMethod() ? $this->request->getDataPost('reportIdToDelete') : 0;

		$this->reports = array();
		$this->notReportedMembers = array();
	}

	public function show()
	{
		require $this->htmlDir . 'ReportHtml.php';
	}

	//============================================================================

	public function getProjectId()
	{
		return $this->projectId;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function getAddReportContent()
	{
		return $this->addReportContent;
	}

	public function setDate($date)
	{
		 $this->date = $date;
	}

	public function addReport($report)
	{
		$this->reports[] = $report;
	}

	public function addNotReportedMember($username)
	{
		$this->notReportedMembers[] = $username;
	}

	public function setIsAddReportSuccess($success)
	{
		$this->isAddReportSuccess = $success;
	}

	public function setAddReportResultMessage($msg)
	{
		$this->addReportResultMessage = $msg;
	}

	public function isAllowAddReport()
	{
		return $this->isAllowAddReport;
	}

	public function setAllowAddReport($allow)
	{
		$this->isAllowAddReport = $allow;
	}

	public function getReportIdToDelete()
	{
		return $this->reportIdToDelete;
	}

	public function setDeleteReportResultMessage($msg)
	{
		$this->deleteReportResultMessage = $msg;
	}

	public function setIsDeleteReportSuccess($success)
	{
		$this->isDeleteReportSuccess = $success;
	}
}
