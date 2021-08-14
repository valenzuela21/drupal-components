<?php

namespace  Drupal\curso_table\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FilerForm extends FormBase
{
  /**@var EntityTypeManagerInterface*/
  private $entityManager;

  /**@var SessionInterface*/
  private $session;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, SessionInterface $session){
    $this->entityManager = $entityTypeManager;
    $this->session = $session;
  }

  public static function create(ContainerInterface $container){
    return new static(
      $container->get('entity_type.manager'),
      $container->get('session')
    );
  }

  public function getFormId()
  {
    return 'curso_table_filter';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $filters = $this->session->get('curso_table_filter', []);

    $type_option = [
      'none' => '- Ninguno -'
    ];

    /** @var  NodeTypeInterface[] $node_types */
    $node_types = $this->entityManager->getStorage('node_type')->loadMultiple();

    foreach ($node_types as $key => $node_type){
      $type_option[$key] = $node_type->label();
    }

    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titulo'),
      '#default_value' => isset($filters['titulo'])? $filters['titulo'] : ''
    ];

    $form['tipo'] = [
      '#type' => 'select',
      '#title' =>'Select element',
      '#options' => $type_option,
      '#default_value' => isset($filters['tipo'])? $filters['tipo'] : 'none'
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' =>  'Filter'
    ];

    $form['actions']['reset'] = [
        '#type' => 'submit',
        '#value' => 'Reset',
        '#submit' => ['::resetSubmit']
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
   $filter = [];
   $filter['titulo'] = $form_state->getValue('titulo');
   $filter['tipo'] = $form_state->getValue('tipo');
   $this->session->set('curso_table_filter', $filter);
  }

  public function resetSubmit(array &$form, FormStateInterface $form_state)
  {
    $this->session->set('curso_table_filter', []);
  }

}
