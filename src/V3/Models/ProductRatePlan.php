<?php

namespace Sideagroup\Zuora\V3\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * @property-read string $id
 * @property-read string $status
 * @property-read string $name
 * @property-read string $description
 * @property-read string $effectiveStartDate
 * @property-read string $effectiveEndDate
 * @property-read string $externalIdSourceSystem
 * @property-read array $externallyManagedPlanIds
 * @property-read array<ProductRatePlanCharge> $productRatePlanCharges
 * @property-read string $productRatePlanNumber
 *
 * @extends parent<string,mixed>
 */
final class ProductRatePlan extends Fluent
{
    public function __construct($attributes = [])
    {
        $attributes['productRatePlanCharges'] = Arr::map($attributes['productRatePlanCharges'], fn ($item) => new ProductRatePlanCharge($item));
        parent::__construct($attributes);
    }
}
