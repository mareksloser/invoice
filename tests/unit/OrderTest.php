<?php

class OrderTest extends \Codeception\Test\Unit
{

	/** @var \Contributte\Invoice\Data\Order */
	private $order;

	/** @var \Contributte\Invoice\Calculators\ICalculator */
	private $calculator;

	protected function _before()
	{
		$this->order = new \Contributte\Invoice\Data\Order('0001', null, null,
			new \Contributte\Invoice\Data\PaymentInformation('$'));

		$this->order->addItem('Foo', 15, 2, 0.10);
		$this->order->addItem('Bar', 30, 3, 0.20);

		$this->calculator = new \Contributte\Invoice\Calculators\BcCalculator(2);
	}

	// tests
	public function testTotalPriceWithoutTax()
	{
		$this->assertSame('120.00', $this->order->getTotalPrice($this->calculator));
	}

	public function testTotalPriceFixed()
	{
		$items = $this->order->getItems();
		$items[0]->setTotalPrice(15);
		$items[1]->setTotalPrice(30);

		$this->assertSame('45.00', $this->order->getTotalPrice($this->calculator));
	}

	public function testTotalPriceWithTax()
	{
		$this->assertSame('141.00', $this->order->getTotalPrice($this->calculator, true));
	}

	public function testTotalPriceFixedWithUseTax()
	{
		$items = $this->order->getItems();
		$items[0]->setTotalPrice(15);
		$items[1]->setTotalPrice(30);

		$this->assertSame('45.00', $this->order->getTotalPrice($this->calculator, true));
	}
}