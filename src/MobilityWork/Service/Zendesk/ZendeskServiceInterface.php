<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Model\Hotel\HotelInterface;
use MobilityWork\Model\LanguageInterface;
use MobilityWork\Model\Security\UserInterface;

interface ZendeskServiceInterface
{
    public function createCustomerTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $message,
        string $reservationNumber,
        ?HotelInterface $hotel,
    ): void;

    public function createHotelTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $city,
        string $hotelName,
        string $message
    ): void;

    public function createPressTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $city,
        string $message
    ): void;

    public function createPartnersTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $message
    ): void;

    /**
     * $userParams = [
     *  'email' => string,
     *  'name' => string,
     *  'phone' => string,
     *  'role' => string,
     *  'user_fields' => [ 'website' => string, 'press_media' => string ]
     * ]
     * @param array $userParams
     * @return UserInterface
     */
    public function createOrUpdateAUser(array $userParams): UserInterface;
}
