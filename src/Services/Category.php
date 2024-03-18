<?php

namespace Yigitbayol\Larasut\Services;

use App\Models\LarasutSetting;
use Illuminate\Support\Facades\Http;


class Category
{

    private $larasut;

    public function __construct(Larasut $larasut)
    {
        $this->larasut = $larasut;
    }

    /**
     * Get All Categories in Parasut
     *
     * @return void
     */
    public function getAll()
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories";

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get All Categories with Filter
     *
     * @param  mixed $query_parameters Query Parameters are : name,page[size],page[number],filter[name],filter[category_type]
     * @return void
     */
    public function getWithFilter($query_parameters)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories?" . http_build_query($query_parameters);

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Category Info for given ID in Parasut
     *
     * @return void
     */
    public function getById($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories/" . $id;

        $response = Http::withToken($this->larasut->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Create Category in Parasut App with given parameters
     *
     * @param  mixed $data
     * @param  mixed $type  Product,Contact,SalesInvoice,Employee,Expenditure
     * @return void
     */
    public function create($name, $type, $parent = 0)
    {

        $category = (object) array(
            'data' =>
                array(
                    'type' => 'item_categories',
                    'attributes' =>
                        array(
                            'name' => $name,
                            'category_type' => $type,
                            'parent_id' => $parent,
                        ),
                ),
        );

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories";

        $response = Http::withToken($this->larasut->getAccessToken())->withBody(json_encode($category), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }


    /**
     * Update Category in Parasut App with given parameters and Id
     *
     * @param  mixed $id Id of Category
     * @param  mixed $name New Name of Category
     * @param  mixed $type Product,Contact,SalesInvoice,Employee,Expenditure
     * @return void
     */
    public function update($id, $name, $type): bool
    {

        $category = array(
            'data' =>
                array(
                    'type' => 'item_categories',
                    'attributes' =>
                        array(
                            'name' => $name,
                            'category_type' => $type,
                            'parent_id' => 0,
                        ),
                ),
        );

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories/" . $id;

        $response = Http::withToken($this->larasut->getAccessToken())->withBody(json_encode($category), 'application/json')
            ->put($url);

        return $response->successful() ? true : false;
    }

    /**
     * Delete Category for given ID in Parasut
     *
     * @return void
     */
    public function delete($id): bool
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories/" . $id;

        $response = Http::withToken($this->larasut->getAccessToken())->delete($url);

        if ($response->successful()) {
            LarasutSetting::updateOrCreate(['id' => 1], [
                'default_customer_category_id' => null
            ]);
        }

        return $response->successful() ? true : false;
    }
}
