<?php namespace Iform\FileSystem;

interface FileSystem {

    public function exists($path);

    public function get($path);

    public function put($path, $contents, $visibility = null);

    public function prepend($path, $data);

    public function append($path, $data);

    public function copyTo($from, $to);

    public function moveTo($from, $to);

}