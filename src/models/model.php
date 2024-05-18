<?php

namespace Models;

/**
 * Parent model to be inherited from by all submodels for common baseline functionality
 */
class Model {
    /**
     * Serialize the model
     * @return array An associative array representation of the model
     */
    public function serialize() {
        return get_object_vars($this);
    }
}