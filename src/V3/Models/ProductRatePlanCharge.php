<?php

namespace Sideagroup\Zuora\V3\Models;

use Illuminate\Support\Fluent;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read string $type
 * @property-read string $model
 * @property-read string $uom
 * @property-read array $pricingSummary
 * @property-read array<array, int> $pricing
 * @property-read float $defaultQuantity
 * @property-read mixed $applyDiscountTo
 * @property-read mixed $discountLevel
 * @property-read mixed $discountClass
 * @property-read bool $applyToBillingPeriodPartially
 * @property-read array $productDiscountApplyDetails
 * @property-read string $endDateCondition
 * @property-read mixed $upToPeriods
 * @property-read mixed $upToPeriodsType
 * @property-read mixed $billingDay
 * @property-read mixed $listPriceBase
 * @property-read mixed $specificListPriceBase
 * @property-read mixed $billingTiming
 * @property-read mixed $ratingGroup
 * @property-read mixed $billingPeriod
 * @property-read mixed $billingPeriodAlignment
 * @property-read mixed $specificBillingPeriod
 * @property-read mixed $smoothingModel
 * @property-read mixed $numberOfPeriods
 * @property-read mixed $overageCalculationOption
 * @property-read mixed $overageUnusedUnitsCreditOption
 * @property-read mixed $unusedIncludedUnitPrice
 * @property-read mixed $usageRecordRatingOption
 * @property-read mixed $priceChangeOption
 * @property-read mixed $priceIncreasePercentage
 * @property-read mixed $useTenantDefaultForPriceChange
 * @property-read bool $taxable
 * @property-read string $taxCode
 * @property-read string $taxMode
 * @property-read mixed $prorationOption
 * @property-read string $triggerEvent
 * @property-read string $description
 * @property-read mixed $revRecCode
 * @property-read mixed $revRecTriggerCondition
 * @property-read string $revenueRecognitionRuleName
 * @property-read mixed $useDiscountSpecificAccountingCode
 * @property-read array $financeInformation
 * @property-read mixed $deliverySchedule
 * @property-read bool $reflectDiscountInNetAmount
 * @property-read bool $isStackedDiscount
 * @property-read mixed $productRatePlanChargeNumber
 *
 * @extends parent<string,mixed>
 */
final class ProductRatePlanCharge extends Fluent {}
