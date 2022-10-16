<?php

namespace MobilityWork\Model\Hotel;

interface HotelInterface
{
    public function getName(): string;
    public function getAddress(): string;
    public function getCurrency(): CurrencyInterface;
}
