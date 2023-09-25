<?php

namespace Drupal\menu_icon;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Exception;
use PDO;

class MenuIconDatabaseHandler {

  /**
   * Contains the entity manager inatance to handle entities.
   * 
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * Contains the database connection object.
   * 
   * @var \Drupal\Core\Database\Connection 
   */
  protected $connection;

  /**
   * Constructs the required dependency for the service.
   * 
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   Contains the entity manager instance.
   * @param \Drupal\Core\Database\Connection $connection
   *   Contains the database connection object.
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, Connection $connection) {
    $this->entityManager = $entity_manager;
    $this->connection = $connection;
  }

  /**
   * Function to set the menu icon data to database.
   */
  public function setMenuIconData(array $data) {
    try {
      $query = $this->connection->merge('menu_icons')->key('menu_item_id', $data['menu_item_id']);
      $query->fields([
        'icon_target_id' => $data['icon_target_id'],
        'icon_alt' => $data['icon_alt'],
        'class_list' => $data['class_list'],
        'show_title' => $data['show_title'],
      ])
      ->execute();

      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }
  
  /**
   * Function to get the menu icon data.
   */
  public function getMenuIconData($menu_id) {
    try {
      if ($menu_id) {
        $query = $this->connection->select('menu_icons', 'm');
        $result = $query->fields('m', [
          'icon_target_id',
          'icon_alt',
          'class_list',
          'show_title',
        ])
        ->condition('m.menu_item_id', $menu_id)
        ->execute()
        ->fetch(PDO::FETCH_ASSOC);

        return $result;
      }
     else {
      return [];
     }
    }
    catch (Exception $e) {
      return [];
    }
  }
}
