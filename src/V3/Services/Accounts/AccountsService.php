<?php

namespace Sideagroup\Zuora\V3\Services\Accounts;

use Sideagroup\Zuora\V3\Models\Account;
use Sideagroup\Zuora\V3\Services\AbstractService;

final class AccountsService extends AbstractService
{
    /**
     * @link https://developer.zuora.com/v1-api-reference/api/operation/GET_Account/
     */
    public function getAccount(string $account_key): ?Account
    {
        $response = $this->client->get("/v1/accounts/$account_key")->throw();

        if (! $response->json('success')) {
            return null;
        }

        return new Account($response->json());
    }

    /**
     * @link https://developer.zuora.com/v1-api-reference/api/operation/POST_Account/
     */
    // public function createAccount(CreateAccountRequest|array $data): bool
    // {
        /** @var ?Account */
        // $account = null;
        // if ($data instanceof CreateAccountRequest) {
        //     $account = $data->();
        // } else {
        //     $account = CreateAccountRequest::fromArray($data)->getAccount();
        // }

        // $response = $this->client->post("/v1/accounts", $account->toArray())->throw();
    // }
}
