<?php

namespace MobilityWork\Builder;

interface BuilderInterface
{
    public function init(): BuilderInterface;
    public function reset(): BuilderInterface;
    public function build(): mixed;
}
