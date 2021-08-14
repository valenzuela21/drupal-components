<?php

namespace  Drupal\curso_table\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TableController extends  ControllerBase
{
  /**@var SessionInterface $session*/
  private $session;
  public function __construct(SessionInterface $session){
        $this->session = $session;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('session')
    );
  }

  public function table(){

    $build =  [];

    $build[] = $this->formBuilder()->getForm('Drupal\curso_table\Form\FilerForm');

    $filter = $this->session->get('curso_table_filter', []);

    $query = $this->entityTypeManager()->getStorage('node')->getQuery();
    $query->sort('created', 'DESC');
    if(isset($filter['titulo'])){
      if(!empty($filter['titulo'])){
        $query->condition('title', $filter['titulo'], 'CONTAINS');
      }
    }
    if(isset($filter['tipo'])){
      if('none' != $filter['tipo']){
        $query->condition('type', [$filter['tipo']], 'IN');
      }
    }

    $query->pager(2);

    $result = $query->execute();
    /**
     * @var NodeInterface[] $nodes
     */
    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($result);

    $file =  [];
    foreach ($nodes as $node){
      $file[] = [
        'data'=>[
            $node->toLink(),
            $node->bundle(),
            $node->getOwner()->toLink(),
            date('d/m/y H:i:s', $node->get('created')->value)
        ]
      ];
    }

    $headers =  [
      'Titulo',
      'Tipo',
      'Autor',
      'Fecha Creación'
    ];


    $table =  [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $file
    ];

    $build[] = $table;
    $build[] = ['#type' => 'pager'];

    return $build;
  }

}
