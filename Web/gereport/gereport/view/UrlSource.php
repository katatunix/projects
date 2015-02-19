<?php

namespace gereport\view;

interface UrlSource
{
	public function getHtmlUrl();

	public function getIndexUrl();
	public function getLoginUrl();
	public function getLogoutUrl();
	public function getReportUrl();
	public function getOptionsUrl();
	public function getChangePasswordUrl();
}
