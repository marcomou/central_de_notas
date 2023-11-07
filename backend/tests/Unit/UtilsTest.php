<?php

namespace Tests\Unit;

use App\Rules\CnpjRule;
use App\Rules\CpfRule;
use App\Utils\Utils;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UtilsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_generate_cpf_valid_correctly()
    {
        $cpfTest = Utils::generateCpf();
        $cpfRule = new CpfRule;

        $this->assertTrue($cpfRule->passes('cpf', $cpfTest));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_generate_cnpj_valid_correctly()
    {
        $cnpjTest = Utils::generateCnpj();
        $cnpjRule = new CnpjRule;

        $this->assertTrue($cnpjRule->passes('cnpj', $cnpjTest));
    }

    public function test_validate_uuid()
    {
        $uuid = Uuid::uuid4()->toString();

        $this->assertTrue(Utils::validateUuid($uuid));
    }

    public function test_invalidate_uuid()
    {
        $uuid = str_repeat('a', 32);

        $this->assertFalse(Utils::validateUuid($uuid));
    }
}
