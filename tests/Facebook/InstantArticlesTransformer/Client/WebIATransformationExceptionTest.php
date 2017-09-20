<?php

namespace Facebook\InstantArticlesTransformer\Client;

class WebIATransformationExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendsException()
    {
        $exception = new WebIATransformationException();

        $this->assertInstanceOf('Exception', $exception);
    }
}
