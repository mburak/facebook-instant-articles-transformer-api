<?php
/**
 * Copyright (c) 2017-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Facebook\InstantArticlesTransformer\Client;

use Facebook\InstantArticles\Transformer\Transformer;
use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Validators\InstantArticleValidator;

/**
* Utility class to transform full HTML documents into Instant Articles
*/
class WebIATransformer
{

  public static function transform($html_url, $rule_json_url)
  {
    if (!empty($json_url)) {
      $rule_json = @file_get_contents($json_url);
      if ($rule_json === false) {
        throw new WebIATransformationException("Failed to retrieve JSON ($rule_json_url).", 1);
      }
    }
    else {
      throw new WebIATransformationException("No input JSON supplied.", 1);
    }

    if (!empty($html_url)) {
      $source_html = @file_get_contents($html_url);
      if ($source_html === false) {
        throw new WebIATransformationException("Failed to retrieve HTML ($html_url).", 1);
      }
    }
    else {
      throw new WebIATransformationException("No input HTML.", 1);
    }

    return transform_content($source_html, $rule_json);
  }

  public function transform_content($source_html, $rule_json) {
    //create a DOMDocument with the HTML markup
    libxml_use_internal_errors(true);
    $document = new \DOMDocument();
    $document->loadHTML($source_html);
    libxml_use_internal_errors(false);

    //load the rules and transform
    $transformer = new Transformer();
    $transformer->loadRules($rule_json);

    $instant_article = InstantArticle::create();
    $transformer->transform($instant_article, $document);

    $instant_article_markup = $instant_article->render(null, true);

    $output = array();
    $output['ia_markup'] = $instant_article_markup;

    $output['transformer_warnings'] = array();
    foreach ($transformer->getWarnings() as $warning) {
      $output['transformer_warnings'][] = $warning->__toString();
    }

    $output['validator_warnings'] = array();
    foreach (InstantArticleValidator::check($instant_article) as $warning) {
      $output['validator_warnings'][] = $warning->__toString();
    }

    return $output;
  }
}
