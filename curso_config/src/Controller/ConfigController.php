<?php

namespace Drupal\curso_config\Controller;

use Drupal\Core\Controller\ControllerBase;

class ConfigController extends ControllerBase
{

  public function home() {

    $config = $this->config('curso_config.nuestra_configuracion');

    dpm($config, 'config');

    return ['#markup' => 'Controlador de configuracion del modulo curso_config'];
  }

}
