<?php

namespace MobilityWork\Service\HotelContacts;

use MobilityWork\Model\Hotel\HotelContactInterface;
use MobilityWork\Model\Hotel\HotelInterface;

interface HotelContactsServiceInterface
{
    public function getMainHotelContact(HotelInterface $hotel): ?HotelContactInterface;
}
