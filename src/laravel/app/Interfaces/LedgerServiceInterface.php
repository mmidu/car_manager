<?php

namespace App\Interfaces;

interface LedgerServiceInterface{
	public function version(): object;

     public function getCar(string $plate):object;
}
