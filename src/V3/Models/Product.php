<?php

namespace Sideagroup\Zuora\V3\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * @property-read string $id
 * @property-read string $sku
 * @property-read string $name
 * @property-read string $description
 * @property-read string $category
 * @property-read string $effectiveStartDate
 * @property-read string $effectiveEndDate
 * @property-read string $productNumber
 * @property-read array<ProductRatePlan> $productRatePlans
 *
 * @extends parent<string,mixed>
 */
final class Product extends Fluent
{
    public function __construct($attributes = [])
    {
        $attributes['productRatePlans'] = Arr::map($attributes['productRatePlans'], fn ($item) => new ProductRatePlan($item));
        parent::__construct($attributes);
    }
}
