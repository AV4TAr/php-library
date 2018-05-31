<?php

use PHPUnit\Framework\TestCase;
use We\DataHub\Document;
use We\DataHub\Fields;


final class DocumentTests extends TestCase {

    // 1. Check we can't pass lowercase namespace
    public function testLowercaseNamespaceNotAllowed(): void {

        $this->expectException('\We\DataHub\Exceptions\InvalidNamespace');

        /** @noinspection PhpUnhandledExceptionInspection */
        $doc = new Document(
            "id",
            "Uppercased",
            "doc_type",
            new Fields()
        );

    }


    // 2. Check we can't pass lowercase document types
    public function testLowercaseDocumentTypeNotAllowed(): void {

        $this->expectException('Exception');

        /** @noinspection PhpUnhandledExceptionInspection */
        $doc = new Document(
            "id",
            "namespace",
            "docType",
            new Fields()
        );

    }



}