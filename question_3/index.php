<?php
/*
## Question 3

Given:
- Item contains the following information (in psuedocode):
```
array (
    id,
    name,
    quantity,
    price
)
```

- A tax rate of 7%
- Access to shipping rate api (no need to find a working one, simply assume the methods exist elsewhere in the system and access them as you will)
- A customer item contains:

```php
array (
    first_name  => 'name',
    last_name   => 'name',
    address => array (
    array (
            address_1
            address_2
            city,
            state,
            zip,
        ),
    ),
)
```

- And an instance of a cart can have only one customer and multiple items.

Question:

Please write two or more classes that allow for the setting and retrieval of the following information:
- Customer Name
- Customer Addresses
- Items in Cart
- Where Order Ships
- Cost of item in cart, including shipping and tax
- Subtotal and total for all items
 */

// NOTE: In a real world app we would not be putting multiple classes into one file.

class Item
{
    // left public for the sake of brevity
    public int $id;
    public string $name;
    public int $quantity;
    public float $price;
}

class Address
{
    // left public for the sake of brevity
    public string $address_1;
    public string $address_2;
    public string $city;
    public string $state;
    public string $zip;
}

class Shipping
{
    private Address $shipping_address;
    private float $tax_rate;
    private float $shipping_rate;

    public function __construct(Address $address)
    {
        $this->shipping_address = $address;
        $this->tax_rate = $this->lookupTaxRate();
        $this->shipping_rate = $this->lookupShippingRate();
    }

    private function lookupTaxRate()
    {
        // assume some sort of look up for variable tax rate based on address
        return 7.0;
    }

    private function lookupShippingRate()
    {
        // assume some sort of lookup for shpping based on the address
        return 8.90;
    }

    // could write individual getters or override __get magic method
    // w modified logic
    public function __get($property)
    {
        $gettable = ['shipping_address', 'tax_rate', 'shipping_rate'];
        if(in_array($property, $gettable) && property_exists($this, $property))
        {
            return $this->$property;
        }
    }

}

class Customer
{
    private string $first_name;
    private string $last_name;
    private array $addresses = [];

    public function __construct(string $first_name, string $last_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    // set all addresses while enforcing Address type
    public function setAddresses(Address ...$addresses)
    {
        $this->addresses = $addresses;
    }

    // add single address while enforcing Address type
    public function addAddress(Address $address)
    {
        array_push($this->addresses, $address);
    }

    // could write individual setters or override __set magic method
    // w modified logic
    public function __set($property, $value)
    {
        $settable = ['first_name', 'last_name'];
        if(in_array($property, $settable) && property_exists($this, $property))
        {
            $this->$property = $value;
        }
    }

    // could write individual getters or override __get magic method
    // w modified logic
    public function __get($property)
    {
        $gettable = ['first_name', 'last_name', 'addresses'];
        if(in_array($property, $gettable) && property_exists($this, $property))
        {
            return $this->$property;
        }
    }
}

class Cart
{
    private Customer $customer;
    private Shipping $shipping;
    private $items = [];

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function setShippingAddress(Address $address)
    {
        $this->shipping = new Shipping($address);
    }

    public function getShippingAddress()
    {
        return $this->shipping->shipping_address;
    }

    public function getSubTotal()
    {
        $subtotal = array_reduce($this->items, function($carry, $item){
            $carry += $item->price;
            return $carry;
        }, 0);
        return $subtotal;
    }

    public function getTotal()
    {
        $subtotal = $this->getSubTotal();
        $tax = $subtotal * ($this->shipping->tax_rate / 100);
        return round($subtotal + $tax + $this->shipping->shipping_rate, 2);
    }

    // set all items while enforcing Item type
    public function setItems(Item ...$items)
    {
        $this->items = $items;
    }

    // add a single item while enforcing Item type
    public function addItem(Item $item)
    {
        array_push($this->items, $item);
    }

    public function getSingleItemCost(Item $item)
    {
        $tax = $item->price * ($this->shipping->tax_rate / 100);
        return round($item->price + $tax + $this->shipping->shipping_rate, 2);
    }

    // could write individual getters or override __get magic method
    // w modified logic
    public function __get($property)
    {
        $gettable = ['items'];
        if(in_array($property, $gettable) && property_exists($this, $property))
        {
            return $this->$property;
        }
    }
}

// create items
$item_1 = new Item();
$item_1->id = 1;
$item_1->name = 'Foo';
$item_1->price = 10.5;
$item_1->quantity = 1;

$item_2 = new Item();
$item_2->id = 2;
$item_2->name = 'Bar';
$item_2->price = 20.0;
$item_2->quantity = 3;

// create customer
$customer = new Customer('Justin', 'Voelkel');
$address = new Address();
$address->address_1 = '1234 Main';
$address->city = 'Cleveland';
$address->state = 'OH';
$address->zip = '44105';
$customer->addAddress($address);

// create cart
$cart = new Cart($customer);

// add items
$cart->setItems(...[$item_1, $item_2]);

// get items in cart
var_dump($cart->items);

// set shipping address - assuming the customer selects this
$cart->setShippingAddress($customer->addresses[0]);

// access customer name and address(es)
var_dump($customer->first_name, $customer->last_name, $customer->addresses);

// get shipping address
var_dump($cart->getShippingAddress());

// get single item cost plus tax and shipping
var_dump($cart->getSingleItemCost($cart->items[0]));

// get items subtotal
var_dump($cart->getSubTotal());

// get items total with tax and shipping
var_dump($cart->getTotal());
?>
