<?php

namespace Facebook\InstantArticlesTransformer\Client;

class WebIATransformerTest extends \PHPUnit_Framework_TestCase
{

  public function testTransformContent()
  {
    $source_html = file_get_contents(__DIR__ . '/example-input.html');
    $rule_json = file_get_contents(__DIR__ . '/example-rules.json');
    $result_markup = file_get_contents(__DIR__ . '/example-output.xml');

    $output = WebIATransformer::transform_content($source_html, $rule_json);

    $this->assertInternalType('array', $output);
    $this->assertEquals($result_markup, $output['ia_markup']."\n");
    $this->assertEquals(1, count($output['transformer_warnings']));
    $this->assertInternalType('array', $output['validator_warnings']);
    print_r($output['transformer_warnings']);
    print_r($output['validator_warnings']);

  }

}
