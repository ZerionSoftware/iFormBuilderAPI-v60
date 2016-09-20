<?php

function variables()
{
    return array(
        [
            "id"         => 20594081,
            "name"       => "my_element",
            "sort_order" => 0
        ],
        [
            "id"         => 20594084,
            "name"       => "my_element_1",
            "sort_order" => 1
        ],
        [
            "id"         => 20594087,
            "name"       => "my_element_2",
            "sort_order" => 2
        ],
        [
            "id"         => 20594090,
            "name"       => "my_element_3",
            "sort_order" => 3
        ],
        [
            "id"         => 20594093,
            "name"       => "my_element_4",
            "sort_order" => 4
        ],
        [
            "id"         => 20594096,
            "name"       => "my_element_5",
            "sort_order" => 5
        ],
        [
            "id"         => 20594129,
            "name"       => "my_element_6",
            "sort_order" => 6
        ],
        [
            "id"         => 20594132,
            "name"       => "my_element_7",
            "sort_order" => 7
        ]
    );
}

function randomVariables()
{
    return array(
        [
            "id"         => 20594081,
            "name"       => "my_element",
            "sort_order" => 5
        ],
        [
            "id"         => 20594084,
            "name"       => "my_element_1",
            "sort_order" => 1
        ],
        [
            "id"         => 20594087,
            "name"       => "my_element_2",
            "sort_order" => 3
        ],
        [
            "id"         => 20594090,
            "name"       => "my_element_3",
            "sort_order" => 2
        ],

        [
            "id"         => 20594093,
            "name"       => "my_element_8"
        ],

        [
            "id"         => 20594096,
            "name"       => "my_element_9"
        ],

        [
            "id"         => 20594093,
            "name"       => "my_element_4",
            "sort_order" => 4
        ],
        [
            "id"         => 20594096,
            "name"       => "my_element_5",
            "sort_order" => 0
        ],
        [
            "id"         => 20594129,
            "name"       => "my_element_6",
            "sort_order" => 6
        ],
        [
            "id"         => 20594132,
            "name"       => "my_element_7",
            "sort_order" => 7
        ]
    );
}

function getRange($x)
{
    return range(0, $x);
}

function moveSortLowToHigh() {
    return  [
        ["id" => "20594081", "sort_order" => 6], ["id" => "20594084", "sort_order" => 7]
    ];
}
function moveSortHighToLow() {
    return [
        ["id" => "20594129", "sort_order" => 0], ["id" => "20594132", "sort_order" => 1]
    ];
}

function noSort() {
    return [
        ["id" => "20594129", "label" => 0], ["id" => "20594132", "label" => 1]
    ];
}

function moveOneSortDoNotAdjustOthersInParameters()
{
    return [
        ["id" => "20594129", "sort_order" => 0], ["id" => "20594081", "name" => 'nothing']
    ];
}


function randomIds()
{
    return [100,90,110,70,120,60,130,81,82,129,0,10,20,30,40,50,61,62,63,66];
}

function first10()
{
    return range(0, 20);
}