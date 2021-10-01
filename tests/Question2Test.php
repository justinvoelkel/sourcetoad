<?php
use PHPUnit\Framework\TestCase;
include (dirname(__FILE__).'/../question_2/index.php');

final class Question2Test extends TestCase
{
    private $data_structure;

    protected function setUp(): void
    {
        $this->data_structure = [
            [
                'foo' => [
                    'bar' => 'baz'
                ],
                'another' => [
                    'one' => [
                        'very' => 'deep'
                    ]
                ]
            ]
        ];
    }

    public function testGetNestedValueReturnsCorrectValue() :void
    {
        $result = get_nested_value($this->data_structure, 'bar');
        $this->assertEquals($result, 'baz');
    }

    public function testGetNestedValueThrowsExceptionOnArrayKey() :void
    {
        // ensure that keys with array values are skipped
        $this->expectException("Exception");
        get_nested_value($this->data_structure, 'foo');
    }

    public function testGetNestedValueThrowsExceptionOnBadKey() :void
    {
        $this->expectException("Exception");
        get_nested_value($this->data_structure, 'bad');
    }
}

?>
