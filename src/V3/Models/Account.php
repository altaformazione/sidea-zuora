<?php

namespace Sideagroup\Zuora\V3\Models;

use Illuminate\Support\Fluent;

/**
 * @property-read array<string,string> $basicInfo
 * @property-read array<string,string> $billingAndPayment
 * @property-read array<string,string> $metrics
 * @property-read array<string,string> $billToContact
 * @property-read array<string,string> $soldToContact
 * @property-read array<string,string> $taxInfo
 *
 * @extends parent<string,mixed>
 */
final class Account extends Fluent {}
