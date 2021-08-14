<?php

namespace Drupal\ww_react_list\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Hello block"),
 *   category = @Translation("Hello World"),
 * )
 */
class ReactListBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['react_list_block'] = [
      '#markup' => '<div id="list-app"></div>',
      '#attached' => [
        'library' => 'ww_react_list/react-list'
      ],
    ];

    return $build;
  }

}
