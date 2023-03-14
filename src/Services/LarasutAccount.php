<?php

namespace Yigit\Larasut\Services;

use Illuminate\Support\Facades\Http;


class LarasutAccount extends Larasut
{
    /**
     * Get All Bank Accounts in Parasut
     *
     * @return void
     */
    public function allAccounts()
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts";

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get All Bank Accounts with Filter
     *
     * @param  mixed $query_parameters page[size],page[number],filter[name],filter[currency],filter[bank_name],filter[bank_branch],filter[account_type],filter[iban]
     * @return void
     */
    public function allAccountsWithFilter($query_parameters)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts?" . http_build_query($query_parameters);

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Bank Account Info for given ID in Parasut
     *
     * @param  mixed $id
     * @return void
     */
    public function getAccountById($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts/" . $id;

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Update Bank Account in Parasut App with given parameters
     *
     * @param  mixed $data balance,name,bank_name,bank_branch,bank_account_no,iban
     * @param  mixed $currency TRL,EUR,USD,GBP - Default TRL
     * @param  mixed $account_type cash,bank,sys - Default bank
     * @param bool $archived Default false
     * @return void
     */
    public function updateAccount($id, $data, $currency = 'TRL', $account_type = 'bank', $archived = false)
    {

        $account = [
            "data" => [
                "id" => $id,
                "type" => "accounts",
                "attributes" => [
                    "balance" => $data->balance,
                    "name" => $data->name,
                    "currency" => $currency,
                    "account_type" => $account_type,
                    "bank_name" => $data->bank_name,
                    "bank_branch" => $data->bank_branch,
                    "bank_account_no" => $data->bank_account_no,
                    "iban" => $data->iban,
                    "archived" => $archived
                ]
            ]
        ];

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts/" . $id;

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($account), 'application/json')
            ->put($url);

        return $response->successful() ? true : false;
    }

    /**
     * Create Bank Account in Parasut App with given parameters
     *
     * @param  mixed $data balance,name,bank_name,bank_branch,bank_account_no,iban
     * @param  mixed $currency TRL,EUR,USD,GBP - Default TRL
     * @param  mixed $account_type cash,bank,sys - Default bank
     * @param bool $archived Default false
     * @return void
     */
    public function createAccount($data, $currency = 'TRL', $account_type = 'bank', $archived = false)
    {
        $account = [
            "data" => [
                "type" => "accounts",
                "attributes" => [
                    "balance" => $data->balance,
                    "name" => $data->name,
                    "currency" => $currency,
                    "account_type" => $account_type,
                    "bank_name" => $data->bank_name,
                    "bank_branch" => $data->bank_branch,
                    "bank_account_no" => $data->bank_account_no,
                    "iban" => $data->iban,
                    "archived" => $archived
                ]
            ]
        ];


        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts";

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($account), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }

    /**
     * Delete Bank Account for given ID in Parasut
     *
     * @return void
     */
    public function deleteAccount($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts/" . $id;

        $response = Http::withToken($this->getAccessToken())->delete($url);

        return $response->successful() ? true : false;
    }


    /**
     * Get All Transactions for Account in Parasut
     *
     * @return void
     */
    public function allTransactionsForAccount($id)
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts/" . $id . "/transactions/";

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Create Debit Transaction for Account in Parasut
     *
     * @param  mixed $id - Account ID
     * @param  mixed $data - date,amount,description
     * @return void
     */
    public function createDebitTransaction($id, $data)
    {
        $transaction = [
            "data" => [
                "type" => "transactions",
                "attributes" => [
                    "date" => $data->date,
                    "amount" => $data->amount,
                    "description" => $data->description
                ]
            ]
        ];

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts/" . $id . "/debit_transactions";

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($transaction), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }

    /**
     * Create Credit Transaction for Account in Parasut
     *
     * @param  mixed $id - Account ID
     * @param  mixed $data - date,amount,description
     * @return void
     */
    public function createCreditTransaction($id, $data)
    {
        $transaction = [
            "data" => [
                "type" => "transactions",
                "attributes" => [
                    "date" => $data->date,
                    "amount" => $data->amount,
                    "description" => $data->description
                ]
            ]
        ];

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/accounts/" . $id . "/credit_transactions";

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($transaction), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }
}
