<?php

namespace Drupal\curso_module\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\curso_module\Services\Repetir;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @Block(
 *   id = "curso_module_curso",
 *   admin_label = @Translation("Nuestro bloque del curso"),
 *   category = @Translation("Curso"),
 * )
 *
 * Class CursoBlock
 * @package Drupal\curso_module\Plugin\Block
 */
class CursoBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * @var Repetir
   */
  private $repetir;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Repetir $repetir)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->repetir = $repetir;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('curso_module.repetir')
    );
  }

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * If a block should not be rendered because it has no content, then this
   * method must also ensure to return no content: it must then only return an
   * empty array, or an empty array with #cache set (with cacheability metadata
   * indicating the circumstances for it being empty).
   *
   * @return array
   *   A renderable array representing the content of the block.
   *
   * @see \Drupal\block\BlockViewBuilder
   */
  public function build() {

    return [
      '#theme' => 'curso_plantilla',
      '#etiqueta' => isset($this->configuration['etiqueta']) ? $this->configuration['etiqueta'] : '',
      '#tipo' => isset($this->configuration['tipo']) ? $this->configuration['tipo'] : '',
    ];

//    return ['#markup' => 'Nuestro bloque personalizado.'];
  }

  public function defaultConfiguration()
  {
    return [
      'etiqueta' => 'Mi etiqueta por defecto',
      'tipo' => 'Mi tipo por defecto',
    ];
  }

  public function blockForm($form, FormStateInterface $form_state)
  {

    $form['etiqueta'] = [
      '#type' => 'textfield',
      '#title' => 'Etiqueta',
      '#default_value' => isset($this->configuration['etiqueta']) ? $this->configuration['etiqueta'] : '',
    ];

    $form['tipo'] = [
      '#type' => 'textfield',
      '#title' => 'Tipo',
      '#default_value' => isset($this->configuration['tipo']) ? $this->configuration['tipo'] : '',
    ];

    return $form;
  }

  public function blockValidate($form, FormStateInterface $form_state)
  {
    parent::blockValidate($form, $form_state);
  }

  public function blockSubmit($form, FormStateInterface $form_state)
  {
    parent::blockSubmit($form, $form_state);

    $this->configuration['etiqueta'] = $form_state->getValue('etiqueta');
    $this->configuration['tipo'] = $form_state->getValue('tipo');
  }

  public function blockAccess(AccountInterface $account)
  {
//    return AccessResult::allowedIfHasPermission($account, 'curso permiso limitado');

    if ( $account->hasPermission('curso permiso limitado')) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden('No tienes acceso por falta de permisos');
    }
  }

}
