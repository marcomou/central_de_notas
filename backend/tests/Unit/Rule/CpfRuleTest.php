<?php

namespace Tests\Unit\Rule;

use App\Rules\CpfRule;
use Tests\TestCase;

class CpfRuleTest extends TestCase
{
    const CPF_FORMATTED = '753.513.400-97';

    const CPF_UNFORMATTED = '19731865004';

    private CpfRule $cpf;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cpf = new CpfRule;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     * 
     * 123456789011121
     */
    public function test_cpf_with_invalid_lenght()
    {
        $this->assertFalse($this->cpf->passes('cpf', 12345678901)); //11
        $this->assertFalse($this->cpf->passes('cpf', 1234567890111)); //13
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cpf_with_same_numbers()
    {
        $this->assertFalse($this->cpf->passes('cpf', str_repeat(0, 11)));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cpf_valid_formatted()
    {
        $this->assertTrue($this->cpf->passes('cpf', self::CPF_FORMATTED));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cpf_valid_unformatted()
    {
        $this->assertTrue($this->cpf->passes('cpf', self::CPF_UNFORMATTED));
    }
}
