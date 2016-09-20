<?php namespace Iform\FileSystem;

/**
 * Class ZerionParameterSorter
 * Use this to help manipulate sort orders
 *
 * @api
 */
class ZerionParameterSorter {

    /**
     * Tracking sorts
     *
     * @var int
     */
    public $userParamSortsTracking = array();
    /**
     * item ids
     *
     * @var array
     */
    private $items = array();
    /**
     * pointer
     *
     * @var int
     */
    private $pointer = null;
    /**
     * key position
     *
     * @var int
     */
    private $key = 0;
    /**
     * total items moved
     *
     * @var int
     */
    private $moved = 0;
    /**
     * do not process
     *
     * @var int
     */
    public static $unchanged = 0;
    /**
     * End points
     *
     * @var array
     */
    private $points = array('high' => 0, 'low' => 0);

    public function hasParameter($needle, &$haystack)
    {
        foreach ($haystack as $key => $value) {
            $keys = array_keys($value);
            if ($needle == $value || is_array($value) && isset($value[$needle]) || ($this->isAssoc($keys) && in_array($needle,
                        $keys))
            ) {
                return true;
            }
        }

        return false;
    }

    private function isAssoc($arrKeys)
    {
        return array_keys($arrKeys) !== $arrKeys;
    }

    private function shiftUnavailable()
    {
        return array_key_exists($this->pointer, $this->userParamSortsTracking);
    }

    public function allPositionsFilled()
    {
        return $this->pointer >= $this->points['high'];
    }

    private function getPointer()
    {
        if (is_null($this->pointer)) {
            $this->pointer = $this->points['low'];
        } else {
            $this->pointer ++;
        }
    }

    private function notInAlgorithmRange($sort)
    {
        return $sort < $this->points['low'];
    }

    /**
     * Should start at lowest position
     *
     * @param $sort
     *
     * @return int
     */
    public function nextAvailablePos($sort)
    {
        if ($this->notInAlgorithmRange($sort)) return $sort;

        $this->getPointer();

        if ($this->shiftUnavailable()) {
            return $this->nextAvailablePos($sort);
        } else {
            $this->moved ++;

            return $this->pointer;
        }
    }

    public function compareSets($sort, $original, $id)
    {
        $this->findEndPoints($sort, $original);

        $this->userParamSortsTracking[$sort] = $original;
        $this->items[$id] = $sort;

        if ($sort == $original) static::$unchanged ++;
    }

    private function findEndPoints($sort, $original)
    {
        $lowPoint = $original < $sort ? $original : $sort;
        $highPoint = $original > $sort ? $original : $sort;

        if ($highPoint > $this->points['high']) $this->points['high'] = $highPoint;
        if ($lowPoint < $this->points['low']) $this->points['low'] = $lowPoint;
    }

    public function noChanges()
    {
        return static::$unchanged === count($this->userParamSortsTracking);
    }

    public function isPassedItem($id)
    {
        return array_key_exists($id, $this->items);
    }

    public function sortAscending(array &$list, $key)
    {
        return uasort($list, function ($itemA, $itemB) use ($list, $key) {
            if (! isset($itemA[$key])) {
                return 1;
            } elseif (! isset($itemB[$key])) {
                return -1;
            }

            return $itemA['sort_order'] < $itemB['sort_order'] ? -1 : 1;
        });
    }

    public function getLookupHash($list, $elementInfo)
    {
        foreach ($list as $item) {
            $target = isset($item['sort_order']) ? $item['sort_order'] : - 1;
            $original = isset($elementInfo[$item['id']]) ? $elementInfo[$item['id']] : - 1;

            if ($target < 0 || $original < 0) continue;
            $this->compareSets($target, $original, $item['id']);
        }
    }
}