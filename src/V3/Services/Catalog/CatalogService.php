<?php

namespace Sideagroup\Zuora\V3\Services\Catalog;

use Illuminate\Support\LazyCollection;
use Sideagroup\Zuora\V3\Models\Product;
use Sideagroup\Zuora\V3\Services\AbstractService;

final class CatalogService extends AbstractService
{
    /**
     * @link https://developer.zuora.com/v1-api-reference/api/operation/GET_Catalog/
     *
     * @return LazyCollection<int, Product>
     */
    public function listProducts(): LazyCollection
    {
        return LazyCollection::make(function () {
            $page = 1;

            do {
                $response = $this->client->get('/v1/catalog/products', [
                    'page' => $page,
                ])->throw();

                $page += 1;

                foreach ($response->collect('products') as $item) {
                    yield new Product((array) $item);
                }
            } while ($response->json('nextPage'));
        });
    }

    /**
     * @param  string  $product_key  The unique ID, SKU, or product number of the product that you want to retrieve.
     *
     * @link https://developer.zuora.com/v1-api-reference/api/operation/GET_Product/
     */
    public function getProduct(string $product_key): ?Product
    {
        $response = $this->client->get("/v1/catalog/products/$product_key")->throw();

        if (! $response->json('success')) {
            return null;
        }

        return new Product($response->json());
    }
}
