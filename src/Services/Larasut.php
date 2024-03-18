<?php

namespace Yigitbayol\Larasut\Services;

use Carbon\Carbon;
use App\Models\LarasutSetting;
use Illuminate\Support\Facades\Http;

class Larasut
{
    private $access_token, $refresh_token, $expire_at, $default_customer_category, $default_sales_invoice_category;

    public $account, $category, $customer, $invoice, $product;

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
        $this->initialize();
        $this->customer = new Customer($this);
        $this->invoice = new Invoice($this);
        $this->account = new Account($this);
        $this->category = new Category($this);
        $this->product = new Product($this);

    }

    public function initialize()
    {
        $tokenDetails = $this->getAccessTokenFromDatabaseOrApi();
        $this->access_token = $tokenDetails['access_token'];
        $this->expire_at = $tokenDetails['expire_at'];
        $this->expire_in = $tokenDetails['expire_in'];
    }

    private function getAccessTokenFromDatabaseOrApi()
    {
        // Önce veritabanından token'i deneyin
        $setting = LarasutSetting::first();
        if ($setting && $setting->access_token && $setting->expires_at > Carbon::now()) {
            return [
                'access_token' => $setting->access_token,
                'expire_at' => $setting->expires_at,
                'expire_in' => $setting->expires_in
            ];
        }

        // Token yoksa veya süresi dolmuşsa, API'den yeni bir token alın
        return $this->fetchAccessTokenFromApi();
    }

    private function fetchAccessTokenFromApi()
    {
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
     * Set Access Token
     *
     * @param  mixed $token
     * @return void
     */
    private function setAccessToken($token)
    {
        $this->access_token = $token;
        // Veritabanında token ve süresini güncelleyin
        LarasutSetting::updateOrCreate(['id' => 1], [
            'access_token' => $token
        ]);
    }

    /**
     * Get Access Token
     *
     * @return void
     */
    public function getAccessToken()
    {
        return $this->access_token = LarasutSetting::first()->access_token;
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
        LarasutSetting::updateOrCreate(['id' => 1], [
            'default_customer_category_id' => $category
        ]);
    }

    /**
     * Get Customer Default Category Id
     *
     * @return void
     */
    public function getCustomerCategoryId()
    {
        return $this->default_customer_category = LarasutSetting::first()->default_customer_category_id;
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
        LarasutSetting::updateOrCreate(['id' => 1], [
            'default_sales_invoice_category_id' => $category
        ]);
    }

    /**
     * Get Sales Invoice Default Category Id
     *
     * @return void
     */
    public function getSalesInvoiceCategoryId()
    {
        return $this->default_sales_invoice_category = LarasutSetting::first()->default_sales_invoice_category_id;
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

        LarasutSetting::updateOrCreate(['id' => 1], [
            'expire_at' => $expire_at
        ]);
    }

    /**
     * Get Token Expire At
     *
     * @return void
     */
    public function getExpireAt()
    {
        return $this->expire_at = LarasutSetting::first()->expire_at;
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

        LarasutSetting::updateOrCreate(['id' => 1], [
            'refresh_token' => $refresh_token
        ]);
    }

    /**
     * Get Refresh Token
     *
     * @return void
     */
    public function getRefreshToken()
    {
        return $this->refresh_token = LarasutSetting::first()->refresh_token;
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

        if (isset ($responseBody["error"]) && $responseBody["error"] == "invalid_grant") {
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
     * @return array
     */
    public function createFirstCategory($name, $type): array
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
