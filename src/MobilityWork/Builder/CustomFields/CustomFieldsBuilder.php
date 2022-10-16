<?php

namespace MobilityWork\Builder\CustomFields;

use MobilityWork\Builder\BuilderInterface;
use MobilityWork\Enum\CustomFieldTypeEnum;
use MobilityWork\ValueObject\CustomFieldsValueObject;

/**
 * @Todo Refacto as a Collection of CustomField objets with a toArray() function
 */
class CustomFieldsBuilder implements BuilderInterface, CustomFieldsBuilderInterface
{
    /**
     * @var array array[string => mixed]
     */
    private array $customFields;

    public function __construct()
    {
        $this->customFields = [];
    }

    public function init(): CustomFieldsBuilder
    {
        $this->reset();
        return $this;
    }

    public function reset(): CustomFieldsBuilder
    {
        $this->customFields = [];
        return $this;
    }

    public function addType(CustomFieldTypeEnum $type): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$type] = $type;
        return $this;
    }

    public function addReservationNumber(string $reservationNumber): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$reservationNumber] = $reservationNumber;
        return $this;
    }

    public function addHotelEmail(string $hotelEmail): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$hotelEmail] = $hotelEmail;
        return $this;
    }

    public function addHotelName(string $hotelName): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$hotelName] = $hotelName;
        return $this;
    }

    public function addHotelAddress(string $hotelAddress): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$hotelAddress] = $hotelAddress;
        return $this;
    }

    public function addHotelCity(string $hotelCity): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$hotelCity] = $hotelCity;
        return $this;
    }

    public function addRoomName(string $roomName): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$roomName] = $roomName;
        return $this;
    }

    public function addRoomPrice(string $roomPrice): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$roomPrice] = $roomPrice;
        return $this;
    }

    public function addBookedDate(string $bookedDate): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$bookedDate] = $bookedDate;
        return $this;
    }

    public function addBookingDates(string $bookingDates): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$bookingDates] = $bookingDates;
        return $this;
    }

    public function addLanguage(string $language): CustomFieldsBuilder
    {
        $this->customFields[CustomFieldsValueObject::$language] = $language;
        return $this;
    }

    public function build(): array
    {
        return $this->customFields;
    }
}
