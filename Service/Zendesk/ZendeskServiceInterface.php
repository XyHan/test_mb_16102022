<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Model\Hotel\HotelInterface;
use MobilityWork\Model\LanguageInterface;
use MobilityWork\Model\Security\UserInterface;

interface ZendeskServiceInterface
{
    public function createCustomerTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        string $reservationNumber,
        LanguageInterface $language,
        ?HotelInterface $hotel,
    ): void;

    public function createHotelTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $city,
        string  $website,
        string $hotelName,
        string $message,
        LanguageInterface $language
    ): void;

    public function createPressTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $city,
        string $media,
        string $message,
        LanguageInterface $language
    ): void;

    public function createPartnersTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        LanguageInterface $language
    ): void;
}
