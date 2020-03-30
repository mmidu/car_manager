<?php

namespace App\Interfaces;

interface LedgerServiceInterface{
	public function version(): object;

    public function getCar(string $plate):object;

    public function getLastTransactionByPlateAndOwner(string $plate, string $owner):object;

    public function postTransaction(array $transaction): object;
}
