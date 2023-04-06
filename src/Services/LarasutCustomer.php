<?php

namespace Yigit\Larasut\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;


class LarasutCustomer extends Larasut
{

    /**
     * Get All Customers in Parasut
     *
     * @return void
     */
    public function allCustomers()
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/contacts";

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get All Customers with Filter
     *
     * @param  mixed $query_parameters page[size],page[number],filter[name],filter[email],filter[tax_number],filter[tax_office],filter[city],filter[account_type]
     * @return void
     */
    public function allCustomersWithFilter($query_parameters)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/contacts?" . http_build_query($query_parameters);

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Customer Info for given ID in Parasut
     *
     * @param  mixed $id
     * @return void
     */
    public function getCustomerById($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/contacts/" . $id;

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Update Customer in Parasut App with given parameters
     *
     * @param  mixed $data email,name,short_name,district,city,address,phone,tax_number,tax_office,category
     * @param  mixed $contact_type person,company
     * @param  mixed $account_type customer,supplier
     * @return void
     */
    public function updateCustomer($id, $data, $contact_type = 'person', $account_type = 'customer')
    {
        $customer = array(
            'data' =>
            array(
                'type' => 'contacts',
                'attributes' => array(
                    'email' => isset($data->email) ? $data->email : "",
                    'name' => $data->name,
                    'short_name' => isset($data->short_name) ? $data->short_name : $data->name,
                    'contact_type' => $contact_type,
                    'district' => isset($data->district) ? $data->district : "",
                    'city' => isset($data->city) ? $data->city : "",
                    'address' => isset($data->address) ? $data->address : "",
                    'phone' => isset($data->phone) ? $data->phone : "",
                    'account_type' => $account_type,
                    'tax_number' => isset($data->tax_number) ? $data->tax_number : "",
                    'tax_office' => isset($data->tax_office) ? $data->tax_office : ""
                ),
                'relationships' => array(
                    'category' => array(
                        'data' => array(
                            'id' => isset($data->category) && !is_null($data->category) ? $data->category : $this->getCustomerCategoryId(),
                            'type' => 'item_categories'
                        ),
                    ),
                ),
            ),
        );


        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/contacts/" . $id;

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($customer), 'application/json')
            ->put($url);

        return $response->successful() ? true : false;
    }

    /**
     * Create Customer in Parasut App with given parameters
     *
     * @param  mixed $data email,name,short_name,district,city,address,phone,tax_number,tax_office,category
     * @param  mixed $contact_type person,company
     * @param  mixed $account_type customer,supplier
     * @return void
     */
    public function createCustomer($data, $contact_type = 'person', $account_type = 'customer')
    {
        $customer = array(
            'data' =>
            array(
                'type' => 'contacts',
                'attributes' => array(
                    'email' => isset($data->email) ? $data->email : "",
                    'name' => $data->name,
                    'short_name' => isset($data->short_name) ? $data->short_name : $data->name,
                    'contact_type' => $contact_type,
                    'district' => isset($data->district) ? $data->district : "",
                    'city' => isset($data->city) ? $data->city : "",
                    'address' => isset($data->address) ? $data->address : "",
                    'phone' => isset($data->phone) ? $data->phone : "",
                    'account_type' => $account_type,
                    'tax_number' => isset($data->tax_number) ? $data->tax_number : "",
                    'tax_office' => isset($data->tax_office) ? $data->tax_office : ""
                ),
                'relationships' => array(
                    'category' => array(
                        'data' => array(
                            'id' => isset($data->category) && !is_null($data->category) ? $data->category : $this->getCustomerCategoryId(),
                            'type' => 'item_categories'
                        ),
                    ),
                ),
            ),
        );


        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/contacts";

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($customer), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }

    /**
     * Delete Customer for given ID in Parasut
     *
     * @return void
     */
    public function deleteCustomer($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/contacts/" . $id;

        $response = Http::withToken($this->getAccessToken())->delete($url);

        return $response->successful() ? true : false;
    }
}
