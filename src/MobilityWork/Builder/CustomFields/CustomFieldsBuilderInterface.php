<?php

namespace MobilityWork\Builder\CustomFields;

use MobilityWork\Enum\CustomFieldTypeEnum;

interface CustomFieldsBuilderInterface
{
    public function addType(CustomFieldTypeEnum $type): CustomFieldsBuilderInterface;
    public function addReservationNumber(string $reservationNumber): CustomFieldsBuilderInterface;
    public function addHotelEmail(string $hotelEmail): CustomFieldsBuilderInterface;
    public function addHotelAddress(string $hotelAddress): CustomFieldsBuilderInterface;
    public function addHotelCity(string $hotelCity): CustomFieldsBuilderInterface;
    public function addRoomName(string $roomName): CustomFieldsBuilderInterface;
    public function addRoomPrice(string $roomPrice): CustomFieldsBuilderInterface;
    public function addBookedDate(string $bookedDate): CustomFieldsBuilderInterface;
    public function addBookingDates(string $bookingDates): CustomFieldsBuilderInterface;
    public function addLanguage(string $language): CustomFieldsBuilderInterface;
}
