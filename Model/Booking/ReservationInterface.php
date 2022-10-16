<?php

namespace MobilityWork\Model\Booking;

use DateTime;
use MobilityWork\Model\Hotel\HotelInterface;
use MobilityWork\Model\Hotel\RoomInterface;

interface ReservationInterface
{
    public function getHotel(): HotelInterface;
    public function getRoom(): RoomInterface;
    public function getBookedDate(): DateTime;
    public function getBookedStartTime(): DateTime;
    public function getBookedEndTime(): DateTime;
    public function getRoomPrice(): float;
    public function getCustomer(): CustomerInterface;
}
