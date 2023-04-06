<?php

namespace Yigit\Larasut\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\LarasutSetting;


class Larasut
{
    private $access_token, $refresh_token, $expire_at, $default_customer_category, $default_sales_invoice_category;

    /**
     * Get Access Token
     * Get Expire At
     * Get Customer Default Category Id
     * Get Sales Invoice Default Category Id
     * Do Authentication
     *
     * @return void
     */
    public function __construct()
    {
        $this->access_token = $this->getAccessToken();
        $this->expire_at = $this->getExpireAt();
        $this->default_customer_category = $this->getCustomerCategoryId();
        $this->default_sales_invoice_category = $this->getSalesInvoiceCategoryId();
        $this->doAuth();
    }

    /**
     * Set Access Token
     *
     * @param  mixed $token
     * @return void
     */
    private function setAccessToken($token)
    {
        $this->access_token = $token;
        LarasutSetting::where('setting_key', 'access_token')->update([
            'setting_value' => $token
        ]);
    }

    /**
     * Get Access Token
     *
     * @return void
     */
    public function getAccessToken()
    {
        return $this->access_token = LarasutSetting::where('setting_key', 'access_token')->first()->setting_value;
    }

    /**
     * Set Customer Default Category Id
     *
     * @param  mixed $category
     * @return void
     */
    private function setCustomerCategoryId($category)
    {
        $this->default_customer_category = $category;
        LarasutSetting::where('setting_key', 'default_customer_category_id')->update([
            'setting_value' => $category
        ]);
    }

    /**
     * Get Customer Default Category Id
     *
     * @return void
     */
    public function getCustomerCategoryId()
    {
        return $this->default_customer_category = LarasutSetting::where('setting_key', 'default_customer_category_id')->first()->setting_value;
    }

    /**
     * Set Sales Invoice Default Category Id
     *
     * @param  mixed $category
     * @return void
     */
    private function setSalesInvoiceCategoryId($category)
    {
        $this->default_sales_invoice_category = $category;
        LarasutSetting::where('setting_key', 'default_sales_invoice_category_id')->update([
            'setting_value' => $category
        ]);
    }

    /**
     * Get Sales Invoice Default Category Id
     *
     * @return void
     */
    public function getSalesInvoiceCategoryId()
    {
        return $this->default_sales_invoice_category = LarasutSetting::where('setting_key', 'default_sales_invoice_category_id')->first()->setting_value;
    }

    /**
     * Set Token Expire At
     *
     * @param  mixed $expire_at
     * @return void
     */
    private function setExpireAt($expire_at)
    {
        $this->expire_at = $expire_at;
        LarasutSetting::where('setting_key', 'expire_at')->update([
            'setting_value' => $expire_at
        ]);
    }

    /**
     * Get Token Expire At
     *
     * @return void
     */
    public function getExpireAt()
    {
        return $this->expire_at = LarasutSetting::where('setting_key', 'expire_at')->first()->setting_value;
    }

    /**
     * Set Refresh Token
     *
     * @param  mixed $refresh_token
     * @return void
     */
    private function setRefreshToken($refresh_token)
    {
        $this->refresh_token = $refresh_token;
        LarasutSetting::where('setting_key', 'refresh_token')->update([
            'setting_value' => $refresh_token
        ]);
    }

    /**
     * Get Refresh Token
     *
     * @return void
     */
    public function getRefreshToken()
    {
        return $this->refresh_token = LarasutSetting::where('setting_key', 'refresh_token')->first()->setting_value;
    }

    /**
     * Do Authentication to Parasut API
     *
     * @return void
     */
    public function doAuth()
    {
        if ($this->access_token == '' || is_null($this->access_token)) {
            $parameters = array(
                'grant_type' => 'password',
                'client_id' => config('larasut.client_id'),
                'client_secret' => config('larasut.client_secret'),
                'redirect_uri' => config('larasut.redirect_uri'),
                "username" => config('larasut.username'),
                "password" => config('larasut.password')
            );

            $response = Http::withBody(json_encode($parameters), 'application/json')
                ->post('https://api.parasut.com/oauth/token');

            $responseBody = json_decode($response->getBody(), true);

            $this->setAccessToken($responseBody['access_token']);
            $this->setRefreshToken($responseBody['refresh_token']);
            $this->setExpireAt(Carbon::now()->addSeconds($responseBody['expires_in']));
        }

        if ($this->expire_at == '' || is_null($this->expire_at) || Carbon::now() > date('Y-m-d H:i', strtotime($this->expire_at))) {
            $this->refreshToken();
        }

        if ($this->default_customer_category == '' || is_null($this->default_customer_category)) {
            $customer_category_id = $this->createFirstCategory(config('larasut.default_customer_category_name'), 'Contact');
            $this->setCustomerCategoryId($customer_category_id);
        }

        if ($this->default_sales_invoice_category == '' || is_null($this->default_sales_invoice_category)) {
            $sales_invoice_category_id = $this->createFirstCategory(config('larasut.default_sales_invoice_category_name'), 'SalesInvoice');
            $this->setSalesInvoiceCategoryId($sales_invoice_category_id);
        }
    }

    /**
     * Get new access token with refresh token
     *
     * @return void
     */
    public function refreshToken()
    {
        $parameters = array(
            'grant_type' => 'refresh_token',
            'client_id' => config('larasut.client_id'),
            'client_secret' => config('larasut.client_secret'),
            'refresh_token' => $this->getRefreshToken()
        );

        $response = Http::withBody(json_encode($parameters), 'application/json')
            ->post('https://api.parasut.com/oauth/token');

        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody["error"]) && $responseBody["error"] == "invalid_grant") {
            $parameters = array(
                'grant_type' => 'password',
                'client_id' => config('larasut.client_id'),
                'client_secret' => config('larasut.client_secret'),
                'redirect_uri' => config('larasut.redirect_uri'),
                "username" => config('larasut.username'),
                "password" => config('larasut.password')
            );

            $response = Http::withBody(json_encode($parameters), 'application/json')
            ->post('https://api.parasut.com/oauth/token');

            $responseBody = json_decode($response->getBody(), true);

            $this->setAccessToken($responseBody['access_token']);
            $this->setRefreshToken($responseBody['refresh_token']);
            $this->setExpireAt(Carbon::now()->addSeconds($responseBody['expires_in']));
        } else {
            $this->setAccessToken($responseBody['access_token']);
            $this->setRefreshToken($responseBody['refresh_token']);
        }
    }

    /**
     * Create First Category in Parasut App with given parameters
     *
     * @param  mixed $data
     * @param  mixed $type  Product,Contact,SalesInvoice,Employee,Expenditure
     * @return void
     */
    public function createFirstCategory($name, $type)
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
}
