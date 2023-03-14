<?php

namespace Yigit\Larasut\Services;

use App\Models\LarasutSetting;
use Illuminate\Support\Facades\Http;


class LarasutCategory extends Larasut
{

    /**
     * Get All Categories in Parasut
     *
     * @return void
     */
    public function allCategories()
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories";

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get All Categories with Filter
     *
     * @param  mixed $query_parameters Query Parameters are : name,page[size],page[number],filter[name],filter[category_type]
     * @return void
     */
    public function allCategoriesWithFilter($query_parameters)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories?" . http_build_query($query_parameters);

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Category Info for given ID in Parasut
     *
     * @return void
     */
    public function getCategoryById($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories/" . $id;

        $response = Http::withToken($this->getAccessToken())->get($url);

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
    public function createCategory($name, $type)
    {

        $category = (object) array(
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

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories";

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($category), 'application/json')
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
    public function updateCategory($id, $name, $type)
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

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($category), 'application/json')
            ->put($url);

        return $response->successful() ? true : false;
    }

    /**
     * Delete Category for given ID in Parasut
     *
     * @return void
     */
    public function deleteCategory($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/item_categories/" . $id;

        $response = Http::withToken($this->getAccessToken())->delete($url);

        if ($response->successful()) {
            LarasutSetting::where('setting_value', $id)->update(['setting_value' => null]);
        }

        return $response->successful() ? true : false;
    }
}
