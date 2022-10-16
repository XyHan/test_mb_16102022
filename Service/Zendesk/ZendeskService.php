<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Model\Hotel\HotelInterface;
use MobilityWork\Model\LanguageInterface;
use Zendesk\API\HttpClient as ZendeskAPI;

class ZendeskService implements ZendeskServiceInterface
{
    public function createCustomerTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        string $reservationNumber,
        HotelInterface $hotel,
        LanguageInterface $language
    ): void
    {
        $reservation = null;

        if (!empty($reservationNumber)) {
            $reservation = $this->getEntityRepository('Reservation')->getByRef($reservationNumber);

            if ($reservation != null) {
                if ($hotel == null) {
                    $hotel = $reservation->getHotel();
                }
            }
        }

        $customFields = [];
        $customFields['80924888'] = 'customer';
        $customFields['80531327'] = $reservationNumber;

        if ($hotel != null) {
            $hotelContact = $this->getServiceManager()->get('service.hotel_contacts')->getMainHotelContact($hotel);
            $customFields['80531267'] = $hotelContact != null ? $hotelContact->getEmail() : null;
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

        $client = new ZendeskAPI($this->getServiceManager()->get('Config')['zendesk']['subdomain']);
        $client->setAuth(
            'basic',
            ['username' => $this->getServiceManager()->get('Config')['zendesk']['username'], 'token' => $this->getServiceManager()->get('Config')['zendesk']['token']]
        );

        $response = $client->users()->createOrUpdate(
            [
                'email' => $email,
                'name' => $firstName.' '.strtoupper($lastName),
                'phone' => !empty($phoneNumber)? $phoneNumber:($reservation != null ? $reservation->getCustomer()->getSimplePhoneNumber() : ''),
                'role' => 'end-user'
            ]
        );

        $client->tickets()->create(
            [
                'requester_id' => $response->user->id,
                'subject'      => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body'  => $message
                    ],
                'priority'      => 'normal',
                'type'          => 'question',
                'status'        => 'new',
                'custom_fields' => $customFields
            ]
        );
    }

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
    ): void
    {
        $customFields = [];
        $customFields['80924888'] = 'hotel';
        $customFields['80918668'] = $hotelName;
        $customFields['80918648'] = $city;
        $customFields['80918708'] = $language->getName();

        $client = new ZendeskAPI($this->getServiceManager()->get('Config')['zendesk']['subdomain']);
        $client->setAuth(
            'basic',
            [
                'username' => $this->getServiceManager()->get('Config')['zendesk']['username'],
                'token' => $this->getServiceManager()->get('Config')['zendesk']['token']
            ]
        );

        $response = $client->users()->createOrUpdate(
            [
                'email' => $email,
                'name' => $firstName.' '.strtoupper($lastName),
                'phone' => $phoneNumber,
                'role' => 'end-user',
                'user_fields' => [ 'website' => $website ]
            ]
        );

        $client->tickets()->create(
            [
                'requester_id' => $response->user->id,
                'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body' => $message
                    ],
                'priority' => 'normal',
                'type' => 'question',
                'status' => 'new',
                'custom_fields' => $customFields
            ]
        );
    }

    public function createPressTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $city,
        string $media,
        string $message,
        LanguageInterface $language
    ): void
    {
        $customFields = [];
        $customFields['80924888'] = 'press';
        $customFields['80918648'] = $city;
        $customFields['80918708'] = $language->getName();

        $client = new ZendeskAPI($this->getServiceManager()->get('Config')['zendesk']['subdomain']);
        $client->setAuth(
            'basic',
            [
                'username' => $this->getServiceManager()->get('Config')['zendesk']['username'],
                'token' => $this->getServiceManager()->get('Config')['zendesk']['token']
            ]
        );

        $response = $client->users()->createOrUpdate(
            [
                'email' => $email,
                'name' => $firstName.' '.strtoupper($lastName),
                'phone' => $phoneNumber,
                'role' => 'end-user',
                'user_fields' => [ 'press_media' => $media ]
            ]
        );

        try {
            $client->tickets()->create(
                [
                    'requester_id' => $response->user->id,
                    'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                    'comment' =>
                        [
                            'body' => $message
                        ],
                    'priority' => 'normal',
                    'type' => 'question',
                    'status' => 'new',
                    'custom_fields' => $customFields
                ]
            );
        } catch (\Exception $e) {
            $this->getLogger()->addError(var_export($response->user->id, true));
        }
    }

    public function createPartnersTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        LanguageInterface $language
    ): void
    {
        $customFields = [];
        $customFields['80924888'] = 'partner';
        $customFields['80918708'] = $language->getName();

        $client = new ZendeskAPI($this->getServiceManager()->get('Config')['zendesk']['subdomain']);
        $client->setAuth(
            'basic',
            [
                'username' => $this->getServiceManager()->get('Config')['zendesk']['username'],
                'token' => $this->getServiceManager()->get('Config')['zendesk']['token']
            ]
        );

        $response = $client->users()->createOrUpdate(
            [
                'email' => $email,
                'name' => $firstName.' '.strtoupper($lastName),
                'phone' => $phoneNumber,
                'role' => 'end-user',
            ]
        );

        $client->tickets()->create(
            [
                'requester_id' => $response->user->id,
                'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body' => $message
                    ],
                'priority' => 'normal',
                'type' => 'question',
                'status' => 'new',
                'custom_fields' => $customFields
            ]
        );
    }
}
