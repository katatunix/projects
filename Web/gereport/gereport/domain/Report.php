<?php

namespace gereport\domain;

interface Report
{
	public function getContent();
	public function getDatetimeAdd();
	public function getMemberUsername();
	public function isPast();
}
