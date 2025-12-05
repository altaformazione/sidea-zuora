<?php

namespace Sideagroup\Zuora\V3\Services\Accounts;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class CreateAccountRequest
{
    protected Validator $validator;

    public function __construct(
        array $input_data
    ) {
        $this->validator = ValidatorFacade::make(
            $input_data,
            $this->rules(),
            $this->messages()
        );
    }

    /**
     * @param  array<string,string>  $account
     */
    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    /**
     * @return array<string,mixed>
     */
    protected function rules(): array
    {
        return [
            'accountNumber' => 'string',
            'additionalEmailAddresses.*' => 'email',
            'autoPay' => 'boolean',
            'batch' => 'string',
            'billCycleDay' => 'integer|min:0|max:31',

            'billToContact.address1' => 'string|max:255',
            'billToContact.address2' => 'string|max:255',
            'billToContact.city' => 'string|max:40',
            'billToContact.country' => 'string',
            'billToContact.county' => 'string|max:32',
            'billToContact.fax' => 'string|max:40',
            'billToContact.firstName' => 'required_if:billToContact|string|max:100',
            'billToContact.homePhone' => 'string|max:40',
            'billToContact.lastName' => 'required_if:billToContact|string|max:100',
            'billToContact.mobilePhone' => 'string|max:40',
            'billToContact.nickname' => 'string',
            'billToContact.otherPhone' => 'string|max:40',
            'billToContact.otherPhoneType' => 'in:Work,Mobile,Home,Other',
            'billToContact.personalEmail' => 'email|max:80',
            'billToContact.state' => 'string',
            'billToContact.taxRegion' => 'string',
            'billToContact.workEmail' => 'email|max:80',
            'billToContact.workPhone' => 'string|max:40',
            'billToContact.zipCode' => 'string|max:20',

            'collect' => 'boolean',
            'communicationProfileId' => 'string',

            'creditCard.cardHolderInfo.addressLine1' => 'required_if:creditCard|string|max:255',
            'creditCard.cardHolderInfo.addressLine2' => 'string|max:255',
            'creditCard.cardHolderInfo.cardHolderName' => 'required_if:creditCard|string|max:50',
            'creditCard.cardHolderInfo.city' => 'required_if:creditCard|string|max:40',
            'creditCard.cardHolderInfo.country' => 'required_if:creditCard|string|max:40',
            'creditCard.cardHolderInfo.email' => 'email|max:80',
            'creditCard.cardHolderInfo.phone' => 'string|max:40',
            'creditCard.cardHolderInfo.state' => 'string',
            'creditCard.cardHolderInfo.zipCode' => 'string|max:20',

            'creditCard.cardNumber' => 'required_if:creditCard|string|max:16',
            'creditCard.cardType' => 'required_if:creditCard|string',
            'creditCard.expirationMonth' => 'required_if:creditCard|int|digits:2',
            'creditCard.expirationYear' => 'required_if:creditCard|int|digits:4',
            'creditCard.securityCode' => 'string',

        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function messages(): array
    {
        return [
        ];
    }

    /**
     * @return array<string,mixed>
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAccount(): array
    {
        return $this->validator->validate(); // throws \Illuminate\Validation\ValidationException
    }
}
