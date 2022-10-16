<?php

namespace MobilityWork\Repository\Reservation;

use MobilityWork\Model\Booking\ReservationInterface;

interface ReservationRepositoryInterface
{
    public function getByRef(string $reservationNumber): ?ReservationInterface;
}
