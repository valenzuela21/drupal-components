<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\curso_module\Services\Repetir;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CursoController extends ControllerBase
{

  /** @var Repetir */
  private $repetir;
  /**
   * @var AccountProxyInterface
   */
  private $accountProxy;

  public function __construct(Repetir $repetir, ConfigFactoryInterface $configFactory, AccountProxyInterface $accountProxy)
  {
    $this->repetir = $repetir;
    $this->configFactory = $configFactory;
    $this->accountProxy = $accountProxy;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('curso_module.repetir'),
      $container->get('config.factory'),
      $container->get('current_user')
    );
  }

  public function home(NodeInterface $node) {

    $resultado = $this->repetir->repetir('curso ');

    return [
      '#theme' => 'curso_plantilla',
      '#etiqueta' => $node->label(),
      '#tipo' => $resultado,
    ];
  }

  public function formController() {

    if (!$this->accountProxy->hasPermission('curso permiso limitado')) {
      return ['#markup' => 'Como usuario no tienes permisos'];
    }

    $form = $this->formBuilder()->getForm('\Drupal\curso_module\Form\CursoForm');

    $build = [];

    $texto = 'Borja';
    $markup = ['#markup' => $this->t('This is the page of the @form', ['@form' => $texto]),];

    $build[] = $markup;
    $build[] = $form;

    return $build;
  }

  public function configCurso() {

    $config = $this->config('system.site');

    dpm($config, 'config');
    dpm($config->get('name'), 'name');

    $configEditable = $this->configFactory->getEditable('system.site');

    dpm($configEditable, 'config editable');

    $configEditable->set('slogan', 'Slogan editado en codigo');
    $configEditable->save();

    return ['#markup' => 'ruta de configuracion'];
  }

}
