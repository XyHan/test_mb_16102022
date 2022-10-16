<?php

namespace MobilityWork\Service\Zendesk;

use DomainException;
use Exception;
use MobilityWork\Client\Http\Zendesk\Strategy\TicketStrategy;
use MobilityWork\Client\Http\Zendesk\Strategy\UserStrategy;
use MobilityWork\Client\Http\Zendesk\ZendeskHttpClientInterface;
use MobilityWork\Exception\NotFoundException;
use MobilityWork\Lib\LoggerInterface;
use MobilityWork\Model\Booking\ReservationInterface;
use MobilityWork\Model\Hotel\HotelContactInterface;
use MobilityWork\Model\Hotel\HotelInterface;
use MobilityWork\Model\LanguageInterface;
use MobilityWork\Model\Security\UserInterface;
use MobilityWork\Model\Security\UserModel;
use MobilityWork\Repository\Reservation\ReservationRepositoryInterface;
use MobilityWork\Service\HotelContacts\HotelContactsServiceInterface;

class ZendeskService implements ZendeskServiceInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly LoggerInterface                $logger,
        private readonly HotelContactsServiceInterface  $hotelContactsService,
        private readonly ZendeskHttpClientInterface     $client,
    ) {}

    public function createCustomerTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $message,
        string $reservationNumber,
        ?HotelInterface $hotel,
    ): void
    {
        $reservation = null;

        if (!empty($reservationNumber)) {
            $reservation = $this->getReservationOrException($reservationNumber);

            if ($hotel == null) {
                $hotel = $reservation->getHotel();
            }
        }

        $customFields = [];
        $customFields['80924888'] = 'customer';
        $customFields['80531327'] = $reservationNumber;

        if ($hotel != null) {
            $hotelContact = $this->getHotelContactOrNull($hotel);
            $customFields['80531267'] = $hotelContact?->getEmail();
            $customFields['80918668'] = $hotel->getName();
            $customFields['80918648'] = $hotel->getAddress();
        }

        if ($reservation != null) {
            $roomName = $reservation->getRoom()->getName() . ' ('.$reservation->getRoom()->getType().')';
            $customFields['80531287'] = $roomName;
            $customFields['80531307'] = $reservation->getBookedDate()->format('Y-m-d');
            $customFields['80924568'] = $reservation->getRoomPrice() . ' ZendeskService.php' .$reservation->getHotel()->getCurrency()->getCode();
            $customFields['80918728'] = $reservation->getBookedStartTime()->format('H:i').' - '.$reservation->getBookedEndTime()->format('H:i');
        }

        $customFields['80918708'] = $language->getName();

        $this->createATicket([
            'requester_id' => $user->getId(),
            'subject'      => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
            'comment' =>
                [
                    'body'  => $message
                ],
            'priority'      => 'normal',
            'type'          => 'question',
            'status'        => 'new',
            'custom_fields' => $customFields
        ]);
    }

    public function createHotelTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $city,
        string $hotelName,
        string $message
    ): void
    {
        $customFields = [];
        $customFields['80924888'] = 'hotel';
        $customFields['80918668'] = $hotelName;
        $customFields['80918648'] = $city;
        $customFields['80918708'] = $language->getName();

        $this->createATicket([
            'requester_id' => $user->getId(),
            'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
            'comment' =>
                [
                    'body' => $message
                ],
            'priority' => 'normal',
            'type' => 'question',
            'status' => 'new',
            'custom_fields' => $customFields
        ]);
    }

    public function createPressTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $city,
        string $message
    ): void
    {
        $customFields = [];
        $customFields['80924888'] = 'press';
        $customFields['80918648'] = $city;
        $customFields['80918708'] = $language->getName();

        $this->createATicket([
            'requester_id' => $user->getId(),
            'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
            'comment' =>
                [
                    'body' => $message
                ],
            'priority' => 'normal',
            'type' => 'question',
            'status' => 'new',
            'custom_fields' => $customFields
        ]);
    }

    public function createPartnersTicket(
        LanguageInterface $language,
        UserInterface $user,
        string $message
    ): void
    {
        $customFields = [];
        $customFields['80924888'] = 'partner';
        $customFields['80918708'] = $language->getName();

        $this->createATicket([
            'requester_id' => $user->getId(),
            'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
            'comment' =>
                [
                    'body' => $message
                ],
            'priority' => 'normal',
            'type' => 'question',
            'status' => 'new',
            'custom_fields' => $customFields
        ]);
    }

    public function createOrUpdateAUser(array $userParams): UserInterface
    {
        try {
            $response = $this->client->post($userParams, new UserStrategy());
        } catch (Exception $e) {
            $message = sprintf(
                'User creation with email %s has failed. Previous: %s',
                $userParams['email'],
                $e->getMessage()
            );
            $this->logger->addError($message);
            throw new ZendeskServiceException($message);
        }

        if (!$response->user->id) throw new DomainException(sprintf('Required id for user %s', $userParams['email']));

        return (new UserModel())->setId($response->user->id);
    }

    private function getReservationOrException(string $reservationNumber): ReservationInterface
    {
        try {
            $reservation = $this->reservationRepository->getByRef($reservationNumber);
        } catch (Exception $exception) {
            $message = sprintf(
                'Get reservation %s has failed. Previous: %s',
                $reservationNumber,
                $exception->getMessage()
            );
            $this->logger->addError($message);
            throw new ZendeskServiceException($message);
        }

        if (!$reservation) {
            throw new NotFoundException(sprintf('Reservation %s not found', $reservationNumber));
        }

        return $reservation;
    }

    private function getHotelContactOrNull(HotelInterface $hotel): ?HotelContactInterface
    {
        try {
            return $this->hotelContactsService->getMainHotelContact($hotel);
        } catch (Exception $e) {
            $message = sprintf(
                'Get hotel %s contact has failed. Previous: %s',
                $hotel->getName(),
                $e->getMessage()
            );
            $this->logger->addError($message);
            throw new ZendeskServiceException($message);
        }
    }

    private function createATicket(array $params): void
    {
        try {
            $this->client->post($params, new TicketStrategy());
        } catch (Exception $e) {
            $message = sprintf(
                'Ticket creation with requester id %s has failed. Previous: %s',
                $params['requester_id'],
                $e->getMessage()
            );
            $this->logger->addError($message);
            throw new ZendeskServiceException($message);
        }
    }
}
