<?php

declare(strict_types=1);

namespace WellRESTed\OpenAPI\Components;

use WellRESTed\Test\TestCase;

class ParameterTest extends TestCase
{
    /** @dataProvider requiredProvider */
    public function testRequiredDefaultsToTrueForPathParametersOnly(In $in, bool $required): void
    {
        $param = new Parameter('name', $in);
        $this->assertEquals($required, $param->required);
    }

    public function requiredProvider(): array
    {
        return [
            [In::PATH, true],
            [In::QUERY, false],
            [In::HEADER, false],
            [In::COOKIE, false]
        ];
    }
}
