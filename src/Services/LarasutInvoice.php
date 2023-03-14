<?php

namespace Yigit\Larasut\Services;

use Illuminate\Support\Facades\Http;


class LarasutInvoice extends Larasut
{
    /**
     * Get All Sales Invoices in Parasut
     *
     * @return void
     */
    public function allInvoices()
    {
        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/sales_invoices";

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get All Sales Invoices with Filter
     *
     * @param  mixed $query_parameters page[size],page[number],filter[issue_date],filter[due_date],filter[contact_id],filter[invoice_id],filter[invoice_series],filter[item_type],filter[print_status],filter[payment_status]
     * @return void
     */
    public function allCustomersWithFilter($query_parameters)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/sales_invoices?" . http_build_query($query_parameters);

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }

    /**
     * Get Sales Invoice Info for given Invoice ID in Parasut
     *
     * @param  mixed $id - Invoice Id
     * @return void
     */
    public function getInvoiceById($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/sales_invoices/" . $id;

        $response = Http::withToken($this->getAccessToken())->get($url);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;
    }


    /**
     * Update Sales Invoice in Parasut App with given parameters
     *
     * @param  mixed $id - Sales Invoice Id
     * @param  mixed $data - description,issue_date,due_date,invoice_series,invoice_id,exchange_rate,withholding_rate,vat_withholding_rate,invoice_discount_type,invoice_discount,billing_address,billing_phone,billing_fax,tax_office,tax_number,country,city,district,order_no,order_date,shipment_addres,payment_account_id,payment_date,payment_description
     * @param  mixed $products - quantity,unit_price,vat_rate,discount_value,description
     * @param  mixed $item_type - invoice,export,estimate,cancelled,recurring_invoice,recurring_estimate,recurring_export,refund - Default invoice
     * @param  mixed $currency - TRL,USD,EUR,GBP - Default TRL
     * @param  mixed $discount_type - percentage,amount - Default percentage
     * @param  mixed $shipment_included - Default true
     * @param  mixed $cash_sale - Default false
     * @param  mixed $is_abroad - Default false
     * @return void
     */
    public function updateInvoice($id, $data, $products, $item_type = 'invoice', $currency = 'TRL', $discount_type = 'percentage', $shipment_included = true, $cash_sale = false, $is_abroad = false)
    {
        foreach ($products as $product) {
            $attributes[] = [
                "quantity" => $product->quantity,
                "unit_price" => $product->unit_price,
                "vat_rate" => $product->vat_rate,
                "discount_type" => $discount_type,
                "discount_value" => $product->discount_value,
                "description" => $product->description
            ];
        }

        $invoice = [
            "data" => [
                "type" => "sales_invoices",
                "attributes" => [
                    "item_type" => $item_type,
                    "description" => $data->description,
                    "issue_date" => isset($data->issue_date) ? $data->issue_date : null,
                    "due_date" => isset($data->due_date) ? $data->due_date : null,
                    "invoice_series" => isset($data->invoice_series) ? $data->invoice_series : null,
                    "invoice_id" => isset($data->invoice_id) ? $data->invoice_id : 0,
                    "currency" => $currency,
                    "exchange_rate" => isset($data->exchange_rate) ? $data->exchange_rate : 0,
                    "withholding_rate" => isset($data->withholding_rate) ? $data->withholding_rate : 0,
                    "vat_withholding_rate" => isset($data->vat_withholding_rate) ? $data->vat_withholding_rate : 0,
                    "invoice_discount_type" => $discount_type,
                    "invoice_discount" => isset($data->invoice_discount) ? $data->invoice_discount : 0,
                    "billing_address" => isset($data->billing_address) ? $data->billing_address : null,
                    "billing_phone" => isset($data->billing_phone) ? $data->billing_phone : null,
                    "billing_fax" => isset($data->billing_fax) ? $data->billing_fax : null,
                    "tax_office" => isset($data->tax_office) ? $data->tax_office : null,
                    "tax_number" => isset($data->tax_number) ? $data->tax_number : null,
                    "country" => isset($data->country) ? $data->country : null,
                    "city" => isset($data->city) ? $data->city : null,
                    "district" => isset($data->district) ? $data->district : null,
                    "is_abroad" => $is_abroad,
                    "order_no" => isset($data->order_no) ? $data->order_no : null,
                    "order_date" => isset($data->order_date) ? $data->order_date : null,
                    "shipment_addres" => isset($data->shipment_addres) ? $data->shipment_addres : null,
                    "shipment_included" => $shipment_included,
                    "cash_sale" => $cash_sale,
                    "payment_account_id" => isset($data->payment_account_id) ? $data->payment_account_id : 0,
                    "payment_date" => isset($data->payment_date) ? $data->payment_date : null,
                    "payment_description" => isset($data->payment_description) ? $data->payment_description : null
                ],
                "relationships" => [
                    "details" => [
                        "data" => [
                            [
                                "type" => "sales_invoice_details",
                                "attributes" => $attributes
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/sales_invoices/" . $id;

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($invoice), 'application/json')
            ->put($url);

        return $response->successful() ? true : false;
    }

    /**
     * Create Sales Invoice in Parasut App
     *
     * @param  mixed $data - description,issue_date,due_date,invoice_series,invoice_id,exchange_rate,withholding_rate,vat_withholding_rate,invoice_discount_type,invoice_discount,billing_address,billing_phone,billing_fax,tax_office,tax_number,country,city,district,order_no,order_date,shipment_addres,payment_account_id,payment_date,payment_description
     * @param  mixed $products - quantity,unit_price,vat_rate,discount_value,description
     * @param  mixed $item_type - invoice,export,estimate,cancelled,recurring_invoice,recurring_estimate,recurring_export,refund - Default invoice
     * @param  mixed $currency - TRL,USD,EUR,GBP - Default TRL
     * @param  mixed $discount_type - percentage,amount - Default percentage
     * @param  mixed $shipment_included - Default true
     * @param  mixed $cash_sale - Default false
     * @param  mixed $is_abroad - Default false
     * @return void
     */
    public function createInvoice($data, $products, $item_type = 'invoice', $currency = 'TRL', $discount_type = 'percentage', $shipment_included = true, $cash_sale = false, $is_abroad = false)
    {
        foreach ($products as $product) {
            $attributes[] = [
                "quantity" => $product->quantity,
                "unit_price" => $product->unit_price,
                "vat_rate" => $product->vat_rate,
                "discount_type" => $discount_type,
                "discount_value" => isset($product->discount_value) ? $product->discount_value : 0,
                "description" => isset($product->description) ? $product->description : null
            ];
        }

        $invoice = [
            "data" => [
                "type" => "sales_invoices",
                "attributes" => [
                    "item_type" => $item_type,
                    "description" => $data->description,
                    "issue_date" => isset($data->issue_date) ? $data->issue_date : null,
                    "due_date" => isset($data->due_date) ? $data->due_date : null,
                    "invoice_series" => isset($data->invoice_series) ? $data->invoice_series : null,
                    "invoice_id" => isset($data->invoice_id) ? $data->invoice_id : 0,
                    "currency" => $currency,
                    "exchange_rate" => isset($data->exchange_rate) ? $data->exchange_rate : 0,
                    "withholding_rate" => isset($data->withholding_rate) ? $data->withholding_rate : 0,
                    "vat_withholding_rate" => isset($data->vat_withholding_rate) ? $data->vat_withholding_rate : 0,
                    "invoice_discount_type" => $discount_type,
                    "invoice_discount" => isset($data->invoice_discount) ? $data->invoice_discount : 0,
                    "billing_address" => isset($data->billing_address) ? $data->billing_address : null,
                    "billing_phone" => isset($data->billing_phone) ? $data->billing_phone : null,
                    "billing_fax" => isset($data->billing_fax) ? $data->billing_fax : null,
                    "tax_office" => isset($data->tax_office) ? $data->tax_office : null,
                    "tax_number" => isset($data->tax_number) ? $data->tax_number : null,
                    "country" => isset($data->country) ? $data->country : null,
                    "city" => isset($data->city) ? $data->city : null,
                    "district" => isset($data->district) ? $data->district : null,
                    "is_abroad" => $is_abroad,
                    "order_no" => isset($data->order_no) ? $data->order_no : null,
                    "order_date" => isset($data->order_date) ? $data->order_date : null,
                    "shipment_addres" => isset($data->shipment_addres) ? $data->shipment_addres : null,
                    "shipment_included" => $shipment_included,
                    "cash_sale" => $cash_sale,
                    "payment_account_id" => isset($data->payment_account_id) ? $data->payment_account_id : 0,
                    "payment_date" => isset($data->payment_date) ? $data->payment_date : null,
                    "payment_description" => isset($data->payment_description) ? $data->payment_description : null
                ],
                "relationships" => [
                    "details" => [
                        "data" => [
                            [
                                "type" => "sales_invoice_details",
                                "attributes" => $attributes
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/sales_invoices";

        $response = Http::withToken($this->getAccessToken())->withBody(json_encode($invoice), 'application/json')
            ->post($url);

        $responseBody = json_decode($response->getBody(), true);

        if ($response->successful()) {
            return $responseBody['data']['id'];
        } else {
            return $responseBody;
        }
    }

    /**
     * Delete Sales Invoice for given ID in Parasut
     *
     * @return void
     */
    public function deleteInvoice($id)
    {

        $url = config('larasut.api_v4_url') . config('larasut.company_id') . "/sales_invoices/" . $id;

        $response = Http::withToken($this->getAccessToken())->delete($url);

        return $response->successful() ? true : false;
    }
}
