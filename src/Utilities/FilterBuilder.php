<?php

namespace Basiq\Utilities;

class FilterBuilder {

    private $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function eq($field, $value) {
        $this->filters[] = $field . ".eq('".$value."')";
        return $this;
    }

    public function gt($field, $value) {
        $this->filters[] = $field . ".gt('".$value."')";
        return $this;
    }

    public function gteq($field, $value) {
        $this->filters[] = $field . ".gteq('".$value."')";
        return $this;
    }

    public function lt($field, $value) {
        $this->filters[] = $field . ".lt('".$value."')";
        return $this;
    }

    public function lteq($field, $value) {
        $this->filters[] = $field . ".lteq('".$value."')";
        return $this;
    }

    public function bt($field, $valueOne, $valueTwo) {
        $this->filters[] = $field . ".bt('".$valueOne."','".$valueTwo."')";
        return $this;
    }

    public function toString() {
        return implode(",", $this->filters);
    }

    public function getFilter() {
        return "filter=" . implode(",", $this->filters);
    }

    public function setFilter($filters) {
        $this->filters = $filters;
        return $this;
    }
}
