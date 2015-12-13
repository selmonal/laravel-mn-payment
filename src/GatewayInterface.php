<?php

namespace Selmonal\Payment;

interface GatewayInterface
{
	/**
	 * @param  BillableInterface $billable
	 * @return RedirectForm
	 */
	function makeRequestForm(BillableInterface $billable);

	/**
	 * @param BillableInterface $billable
	 * @param array $params
	 * @return ResponseInterface
	 */
	function handleResponse(BillableInterface $billable, $params = []);
}