<?php

use Iform\FileSystem\ZerionParameterSorter;

require_once __DIR__ . '/../Fixtures/testArray.php';

class ZerionParameterSorterTest extends \PHPUnit_Framework_TestCase {

    private $params = [
        ["id" => "20594081", "sort_order" => 6], ["id" => "20594084", "sort_order" => 7]
    ];
    private $params4 = [
        ["id" => "20594129", "sort_order" => 0], ["id" => "20594132", "sort_order" => 1]
    ];
    private $params2 = [
        ["id" => "20594090", "sort_order" => 3], ["id" => "20594093", "sort_order" => 4], ["id" => "20594096", "sort_order" => 5]
    ];
    private $params3 = [
        ["id" => "20594093", "sort_order" => 5], ["id" => "20594084", "sort_order" => 6], ["id" => "20594096", "sort_order" => 7]
    ];

    private $params5 = [
        ["id" => "20594093", "sort_order" => 5], ["id" => "20594084", "sort_order" => 6], ["id" => "20594096", "sort_order" => 7]
    ];

    private $currentElementSortOrder = [
        20594081 => 0,
        20594084 => 1,
        20594087 => 2,
        20594090 => 3,
        20594093 => 4,
        20594096 => 5,
        20594129 => 6,
        20594132 => 7
    ];
    private $currentElementSortOrder2 = [
        20594090 => 0, //close
        20594081 => 1,  //close
        20594093 => 2,
        20594084 => 3,
        20594096 => 4,
        20594087 => 5,
        20594129 => 6,
        20594132 => 7
    ];

    private $sorter;

    function setUp(){
        $this->sorter = $sorter = new ZerionParameterSorter();
    }

    function testHasParameterDoesNotReturnFalsePositive()
    {
        $items =  noSort();
        $this->assertFalse($this->sorter->hasParameter('sort_order', $items));
    }

    function testHasParameterFindsParam()
    {
        $items = noSort();

        $this->assertTrue($this->sorter->hasParameter('label', $items));
    }

    function testNextAvailablePosHighToLow()
    {
        $sorter = new ZerionParameterSorter();
        $sorter->getLookupHash($this->params4, $this->currentElementSortOrder);
        $test = array();
        foreach ($this->currentElementSortOrder2 as $id => $sort) {
            if ($sorter->allPositionsFilled()) break;
            if (! $sorter->isPassedItem($id)) {
                $pos = $sorter->nextAvailablePos($sort);
                if ($pos != $sort) {
                    $sorter->userParamSortsTracking[$pos] = $pos;
                    $test[$id] = $pos;
                }
            }
        }

        $expected = array(
            20594090 => 2,
            20594081 => 3,
            20594093 => 4,
            20594084 => 5,
            20594096 => 6,
            20594087 => 7
        );

        $this->assertEquals($expected, $test);
    }

    function testNextAvailablePosLowToHigh()
    {
        $sorter = new ZerionParameterSorter();
        $sorter->getLookupHash($this->params, $this->currentElementSortOrder);
        $test = array();
        foreach ($this->currentElementSortOrder as $id => $sort) {
            if ($sorter->allPositionsFilled()) break;
            if (! $sorter->isPassedItem($id)) {
                $pos = $sorter->nextAvailablePos($sort);
                if ($pos != $sort) {
                    $sorter->userParamSortsTracking[$pos] = $pos;
                    $test[$id] = $pos;
                }
            }
        }

        $expected = array(
            20594087 => 0,
            20594090 => 1,
            20594093 => 2,
            20594096 => 3,
            20594129 => 4,
            20594132 => 5
        );

        $this->assertEquals($expected, $test);
    }

    function testPassOneSortChange()
    {
        $this->sorter->getLookupHash(
            moveOneSortDoNotAdjustOthersInParameters(),
            $this->currentElementSortOrder
        );

        $test = array();
        foreach ($this->currentElementSortOrder as $id => $sort) {
            if ($this->sorter->allPositionsFilled()) break;
            if (! $this->sorter->isPassedItem($id)) {
                $pos = $this->sorter->nextAvailablePos($sort);
                if ($pos != $sort) {
                    $this->sorter->userParamSortsTracking[$pos] = $pos;
                    $test[$id] = $pos;
                }
            }
        }

        $expected = array(
            20594081 => 1,
            20594084 => 2,
            20594087 => 3,
            20594090 => 4,
            20594093 => 5,
            20594096 => 6
        );

        $this->assertEquals($expected, $test);
    }

    function testSortFnOrdersByAsc()
    {
        $sorted = randomVariables();
        $this->sorter->sortAscending($sorted, 'sort_order');

        $test = array_shift($sorted);
        $this->assertEquals($test['sort_order'], 0);
    }

    function testSortFnMovesParameterWithNoSortToEnd()
    {
        $sorted = randomVariables();
        $this->sorter->sortAscending($sorted, 'sort_order');

        $test = array_pop($sorted);
        $this->assertArrayNotHasKey('sort_order', $test);
    }

    function testDeleteResourceSortAdjust()
    {
        $updateSortOrders = randomIds();
        sort($updateSortOrders);
        $updateSortOrders[] = 2147483647;

        $adjustment = range(0, 20);
        $updateSortOrderSql = "UPDATE ZCElement SET sort_order = sort_order - ? WHERE sort_order > ? AND sort_order <= ? AND page_id = ?";

        $n = 0;
        for ($offset = 1; $offset <= count($updateSortOrders) - 1; $offset ++) {
        }
    }

    function tearDown()
    {
        unset($this->sorter);
    }
}
