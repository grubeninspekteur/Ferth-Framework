<?php

namespace DITest;

class A implements iA {}

class B implements iA {}

interface iA {}

function callback_function() {
    return new A;
}

function create_object_for($classname) {
    return new $classname;
}

function resolve_me(iA $object, $ignoredefaults = true)
{
    return $object;
}

interface TagInterface {}

class ExpectsAniA {
    public function __construct(iA $object)
    {
        $this->object = $object;
    }
}

class ExpectsAniATagged extends ExpectsAniA implements TagInterface {}

class C {
    public function __construct($value)
    {
        $this->value = $value;
    }
}

class WithParameters {
    public function __construct(B $object, $value, $ingoredefaults = true)
    {
        $this->value = $value;
    }
}

?>
