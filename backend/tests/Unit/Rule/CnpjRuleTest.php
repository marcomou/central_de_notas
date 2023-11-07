<?php

namespace Tests\Unit\Rule;

use App\Rules\CnpjRule;
use Tests\TestCase;

class CnpjRuleTest extends TestCase
{
    const CNPJ_FORMATTED = '55.326.452/0001-50';

    const CNPJ_UNFORMATTED = '74797792000103';

    private CnpjRule $cnpj;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cnpj = new CnpjRule;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     * 
     * 123456789011121
     */
    public function test_cnpj_with_invalid_lenght()
    {
        $this->assertFalse($this->cnpj->passes('cnpj', 1234567890111)); //13
        $this->assertFalse($this->cnpj->passes('cnpj', 123456789011121)); //15
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cnpj_with_same_numbers()
    {
        $this->assertFalse($this->cnpj->passes('cnpj', str_repeat(0, 14)));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cnpj_valid_formatted()
    {
        $this->assertTrue($this->cnpj->passes('cnpj', CnpjRuleTest::CNPJ_FORMATTED));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cnpj_valid_unformatted()
    {
        $this->assertTrue($this->cnpj->passes('cnpj', CnpjRuleTest::CNPJ_UNFORMATTED));
    }
}
