<?php
use PHPUnit\Framework\TestCase;
include (dirname(__FILE__).'/../question_3/index.php');

final class Question3Test extends TestCase
{
    private $customer;
    private $address;
    protected function setUp():void
    {
        $this->customer = new Customer('test', 'person');
        $this->address = new Address();
        $this->address->address_1 = '1234 Main St.';
        $this->address->city = 'Cleveland';
        $this->address->state = 'OH';
        $this->address->zip = '44105';
    }

    public function testMutabilityOfCustomerAddressesProperty() :void
    {
        // try setting addresses directly on instance
        $this->customer->addresses = ['bad'];
        $this->assertTrue(empty($this->customer->addresses));
    }

    public function testCustomerMagicMethodSetterWorks() :void
    {
        $this->customer->first_name = 'Justin';
        $this->customer->last_name = 'Voelkel';
        $this->assertEquals($this->customer->first_name, 'Justin');
        $this->assertEquals($this->customer->last_name, 'Voelkel');
    }

    public function testCustomerAddressSettersWork() :void
    {
        $this->customer->setAddresses(...[$this->address]);
        $this->assertEquals($this->customer->addresses[0], $this->address);
    }
}
?>
