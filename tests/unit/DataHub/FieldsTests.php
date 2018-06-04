<?php

use PHPUnit\Framework\TestCase;
use We\DataHub\Fields;


final class FieldsTest extends TestCase {


    /**
     *  Verifies it is not possible to pass non-`stringy` values
     */
    public function testWrongTypesAreNotAllowed(): void {

        $fields = new Fields();
        $errRaised = false;

        // We use a random type that is not coerced to string by the language like an int would.
        try {
            $fields->append(1, new DateTime());
        } catch (TypeError $e) {
            $errRaised = true;
        } catch (Exception $e) {
            $errRaised = true;
        }

        $this->assertTrue($errRaised);

    }

    /**
     * Verifies fields serialization yields a json string with the expected format.
     */
    public function testSerializedToJson(): void{

        $fields = new Fields();

        $expected = '{"name":"fede","loco":"true"}';

        try {
            $fields
                ->append("name", "fede")
                ->append("loco", "true");
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $encoded = json_encode($fields);

        $this->assertJsonStringEqualsJsonString($expected, $encoded);

    }

}