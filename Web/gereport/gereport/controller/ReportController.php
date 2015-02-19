<?php

namespace gereport\controller;

__import('controller/controller');
__import('transaction/AddReportTransaction');
__import('transaction/DeleteReportTransaction');
__import('transaction/GetReportsTransaction');
__import('transaction/CheckMemberInProjectTransaction');
__import('utils/DatetimeUtils');

use gereport\transaction\AddReportTransaction;
use gereport\transaction\CheckMemberInProjectTransaction;
use gereport\transaction\DeleteReportTransaction;
use gereport\transaction\GetReportsTransaction;
use gereport\utils\DatetimeUtils;
use gereport\view\ReportView;

class ReportController extends Controller
{
	/**
	 * @var ReportView
	 */
	private $reportView;

	public function __construct($reportView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->reportView = $reportView;
	}

	public function process()
	{
		if ($this->reportView->isPostMethod() && $this->toolbox->session->isLogged())
		{
			if ($this->reportView->getReportIdToDelete())
			{
				$this->processDeleteReport();
			}
			else
			{
				$this->processAddReport();
			}
		}

		$this->processGetReports();
		$this->processCheckAllowAddReport();

		return $this->reportView;
	}

	private function processAddReport()
	{
		$tx = new AddReportTransaction(
			$this->toolbox->session->getLoggedMemberId(),
			$this->reportView->getProjectId(),
			$this->reportView->getDate(),
			DatetimeUtils::getCurDatetime(),
			$this->reportView->getAddReportContent(),
			$this->toolbox->database);
		$success = true;
		try
		{
			$tx->execute();
		}
		catch (\Exception $ex)
		{
			$this->reportView->setAddReportResultMessage($ex->getMessage());
			$success = false;
		}
		if ($success)
		{
			$this->reportView->setAddReportResultMessage('Report was submited OK');
		}
		$this->reportView->setIsAddReportSuccess($success);
	}

	private function processDeleteReport()
	{
		$tx = new DeleteReportTransaction($this->reportView->getReportIdToDelete(), $this->toolbox->database);
		$success = true;
		try
		{
			$tx->execute();
		}
		catch (\Exception $ex)
		{
			$this->reportView->setDeleteReportResultMessage($ex->getMessage());
			$success = false;
		}
		if ($success)
		{
			$this->reportView->setDeleteReportResultMessage('Report was deleted OK');
		}
		$this->reportView->setIsDeleteReportSuccess($success);
	}

	private function processGetReports()
	{
		$tx = new GetReportsTransaction(
			$this->reportView->getProjectId(),
			$this->reportView->getDate(),
			$this->toolbox->session->getLoggedMemberId(),
			$this->toolbox->database);
		try { $tx->execute(); } catch (\Exception $ex) { $this->toolbox->redirector->toIndex(); }

		$this->reportView->setTitle($tx->getProjectName());

		foreach ($tx->getReports() as $report)
		{
			$this->reportView->addReport($report);
		}

		foreach ($tx->getNotReportedMembers() as $username)
		{
			$this->reportView->addNotReportedMember($username);
		}

		$this->reportView->setDate($tx->getDate());
	}

	private function processCheckAllowAddReport()
	{
		$result = false;
		if ($this->toolbox->session->isLogged())
		{
			$tx = new CheckMemberInProjectTransaction(
						$this->toolbox->session->getLoggedMemberId(),
						$this->reportView->getProjectId(),
						$this->toolbox->database);
			$tx->execute();
			$result = $tx->isMemberInProject();
		}

		$this->reportView->setAllowAddReport($result);
	}
}
