<?php namespace Iform\FileSystem;

class ParseCsvOptionBridge extends ParseCSV {

    private $header = array();

    function getHeader()
    {
        return $this->header;
    }

    function getFields()
    {
        return $this->fields;
    }
    /**
     * String to 2D array
     * @param null $data
     * @override
     *
     * @return array
     */
    function parse_string ($data = null) {
        if ( empty($data) ) {
            if ( $this->_check_data() ) {
                $data = &$this->file_data;
            } else return false;
        }

        $rows = array();
        $row = array();
        $row_count = 0;
        $current = '';
        $head = ( !empty($this->fields) ) ? $this->fields : array() ;
        $col = 0;
        $enclosed = false;
        $was_enclosed = false;
        $strlen = strlen($data);

        // walk through each character
        for ( $i=0; $i < $strlen; $i++ ) {
            $ch = $data{$i};
            $nch = ( isset($data{$i+1}) ) ? $data{$i+1} : false ;
            $pch = ( isset($data{$i-1}) ) ? $data{$i-1} : false ;
            // open and closing quotes
            if ( $ch == $this->enclosure && (!$enclosed || $nch != $this->enclosure) ) {
                $enclosed = ( $enclosed ) ? false : true ;
                if ( $enclosed ) {
                    $was_enclosed = true;
                }
                // inline quotes
            } elseif ( $ch == $this->enclosure && $enclosed ) {
                $current .= $ch;
                $i++;
                // end of field/row
            } elseif ( ($ch == $this->delimiter || ($ch == "\n" && $pch != "\r") || $ch == "\r") && !$enclosed ) {
                if ( !$was_enclosed ) {
                    $current = trim($current);
                }
                if (! empty($head)) {
                    $this->header = $head;
                    if ($pch == $this->enclosure) {
                        $current = ltrim($current);
                    }
                }
                $key = ( !empty($head[$col]) ) ? $head[$col] : $col ;

                if ($key == 'label') $current = ltrim($current);

                $row[$key] = $current;
                $current = '';
                $col++;

                // end of row
                if ( $ch == "\n" || $ch == "\r" ) {
                    if ( $this->_validate_offset($row_count) && $this->_validate_row_conditions($row, $this->conditions) ) {
                        if ( $this->heading && empty($head) ) {
                            $head = $row;
                        } elseif ( empty($this->fields) || (!empty($this->fields) && (($this->heading && $row_count > 0) || !$this->heading)) ) {
                            if ( !empty($this->sort_by) && !empty($row[$this->sort_by]) ) {
                                if ( isset($rows[$row[$this->sort_by]]) ) {
                                    $rows[$row[$this->sort_by].'_0'] = &$rows[$row[$this->sort_by]];
                                    unset($rows[$row[$this->sort_by]]);
                                    for ( $sn=1; isset($rows[$row[$this->sort_by].'_'.$sn]); $sn++ ) {}
                                    $rows[$row[$this->sort_by].'_'.$sn] = $row;
                                } else $rows[$row[$this->sort_by]] = $row;
                            } else {
                                $rows[] = $row;
                            }
                        }
                    }
                    $row = array();
                    $col = 0;
                    $row_count++;
                    if ( $this->sort_by === null && $this->limit !== null && count($rows) == $this->limit ) {
                        $i = $strlen;
                    }
                }

                // append character to current field
            } else {
                $current .= $ch;
            }
        }
        $this->titles = $head;
        if ( !empty($this->sort_by) ) {
            ( $this->sort_reverse ) ? krsort($rows) : ksort($rows) ;
            if ( $this->offset !== null || $this->limit !== null ) {
                $rows = array_slice($rows, ($this->offset === null ? 0 : $this->offset) , $this->limit, true);
            }
        }
        return $rows;
    }
}