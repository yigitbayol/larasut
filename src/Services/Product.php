<?php

namespace Yigitbayol\Larasut\Services;

use Illuminate\Support\Facades\Http;

class Product
{
    private $larasut;

    public function __construct(Larasut $larasut)
    {
        $this->larasut = $larasut;
    }

    /**
     * Get All Products in Parasut
     *
     * @return void
     */
    public function getAll()
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/products";

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get All Products with Filter
     *
     * @param  mixed $query_parameters page[size],page[number],filter[name],filter[code]
     * @return void
     */
    public function getWithFilter($query_parameters)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/products?" . http_build_query($query_parameters);

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Product Info for given ID in Parasut
     *
     * @param  mixed $id
     * @return void
     */
    public function getById($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/products/" . $id;

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Product Inventory Levels for given Product in Parasut
     *
     * @param  mixed $id - Product Id
     * @return void
     */
    public function getInventoryLevels($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/product/" . $id . "/inventory_levels";

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Update Product in Parasut App with given parameters
     *
     * @param  mixed $id - Product Id
     * @param  mixed $data - code,name,vat_rate,sales_excise_duty,sales_excise_duty_type,purchase_excise_duty,purchase_excise_duty_type,unit,communications_tax_rate,list_price,buying_price,initial_stock_count,gtip,barcode
     * @param  mixed $currency - TRL,EUR,USD,GBP - Default TRL
     * @param  mixed $buying_currency - TRL,EUR,USD,GBP - Default TRL
     * @param  bool $archived - Default false
     * @param  bool $inventory_tracking - Default true
     * @return void
     */
    public function update($id, $data, $currency = 'TRL', $buying_currency = 'TRL', $archived = false, $inventory_tracking = true)
    {
        $product = [
            "data" => [
                "type" => "products",
                "attributes" => [
                    "code" => isset ($data->code) ? $data->code : null,
                    "name" => $data->name,
                    "vat_rate" => isset ($data->vat_rate) ? $data->vat_rate : 0,
                    "unit" => isset ($data->unit) ? $data->unit : null,
                    "communications_tax_rate" => isset ($data->communications_tax_rate) ? $data->communications_tax_rate : 0,
                    "archived" => $archived,
                    "list_price" => isset ($data->list_price) ? $data->list_price : 0,
                    "currency" => $currency,
                    "buying_price" => isset ($data->buying_price) ? $data->buying_price : 0,
                    "buying_currency" => $buying_currency,
                    "inventory_tracking" => $inventory_tracking,
                    "initial_stock_count" => isset ($data->initial_stock_count) ? $data->initial_stock_count : 0,
                    "gtip" => isset ($data->gtip) ? $data->gtip : null,
                    "barcode" => isset ($data->barcode) ? $data->barcode : null
                ],
                "relationships" => [
                    "category" => [
                        "data" => [
                            "id" => isset ($data->category) ? $data->category : null,
                            "type" => "item_categories"
                        ]
                    ]
                ]
            ]
        ];


        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/products/" . $id;

        $response = Http::withToken($this->larasut->getAccessToken())->withBody(json_encode($product), 'application/json')
            ->put($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;

        // return $response->successful() ? true : false;
    }

    /**
     * Create Product in Parasut App with given parameters
     *
     * @param  mixed $id - Product Id
     * @param  mixed $data - code,name,vat_rate,sales_excise_duty,sales_excise_duty_type,purchase_excise_duty,purchase_excise_duty_type,unit,communications_tax_rate,list_price,buying_price,initial_stock_count,gtip,barcode
     * @param  mixed $currency - TRL,EUR,USD,GBP - Default TRL
     * @param  mixed $buying_currency - TRL,EUR,USD,GBP - Default TRL
     * @param  bool $archived - Default false
     * @param  bool $inventory_tracking - Default true
     * @return void
     */
    public function create($data, $currency = 'TRL', $buying_currency = 'TRL', $archived = false, $inventory_tracking = true)
    {
        $product = [
            "data" => [
                "type" => "products",
                "attributes" => [
                    "code" => isset ($data->code) ? $data->code : null,
                    "name" => $data->name,
                    "vat_rate" => isset ($data->vat_rate) ? $data->vat_rate : 0,
                    "unit" => isset ($data->unit) ? $data->unit : null,
                    "communications_tax_rate" => isset ($data->communications_tax_rate) ? $data->communications_tax_rate : 0,
                    "archived" => $archived,
                    "list_price" => isset ($data->list_price) ? $data->list_price : 0,
                    "currency" => $currency,
                    "buying_price" => isset ($data->buying_price) ? $data->buying_price : 0,
                    "buying_currency" => $buying_currency,
                    "inventory_tracking" => $inventory_tracking,
                    "initial_stock_count" => isset ($data->initial_stock_count) ? $data->initial_stock_count : 0,
                    "gtip" => isset ($data->gtip) ? $data->gtip : null,
                    "barcode" => isset ($data->barcode) ? $data->barcode : null
                ],
                "relationships" => [
                    "category" => [
                        "data" => [
                            "id" => isset ($data->category) ? $data->category : null,
                            "type" => "item_categories"
                        ]
                    ]
                ]
            ]
        ];

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/products";

        $response = Http::withToken($this->larasut->getAccessToken())->withBody(json_encode($product), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }

    /**
     * Delete Product for given ID in Parasut
     *
     * @return void
     */
    public function delete($id): bool
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/products/" . $id;

        $response = Http::withToken($this->larasut->getAccessToken())->delete($url);

        return $response->successful() ? true : false;
    }
}
