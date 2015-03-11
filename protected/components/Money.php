<?php
class Money
{
	public static function priceFormat($price){
		$result = sprintf("%.2f", $price);
		return $result;
	}
}