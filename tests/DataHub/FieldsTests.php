<?php

use PHPUnit\Framework\TestCase;
use We\DataHub\Fields;


final class FieldsTest extends TestCase {

    public function testWrongTypesAreNotAllowed(): void {

        $fields = new Fields();
        $errRaised = false;

        // We use a random type that is not coerced to string by the language.
        try {
            $fields->append(1, new DateTime());
        } catch (TypeError $e) {
            $errRaised = true;
        } catch (Exception $e) {
            $errRaised = true;
        }

        $this->assertTrue($errRaised);

    }

    // 2. Check we can serialize fields as expected.
    public function testSerializedToJson(): void{

        $fields = new Fields();

        $expected = '{"name":"fede","loco":"true"}';

        try {
            $fields->append("name", "fede")->append("loco", "true");
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $encoded = json_encode($fields);

        $this->assertJsonStringEqualsJsonString($expected, $encoded);

    }

}