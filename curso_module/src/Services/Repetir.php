<?php

namespace Drupal\curso_module\Services;


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class Repetir
{

  use StringTranslationTrait;

  /** @var MessengerInterface */
  private $messenger;

  /**
   * @var EntityTypeManagerInterface
   */
  private $entityTypeManager;

  public function __construct(MessengerInterface $messenger, EntityTypeManagerInterface $entityTypeManager)
  {
    $this->messenger = $messenger;
    $this->entityTypeManager = $entityTypeManager;
  }

  public function repetir($palabra, $cantidad = 3) {
    $this->t('This is the page of the form');
    $this->messenger->addError('Esto es un error del servicio repetir.');
    return str_repeat($palabra, $cantidad);
  }

}
