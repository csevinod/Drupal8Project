<?php

namespace Drupal\csvdata_importer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Component\Utility\Html;
use Drupal\Core\Link;



/**
 * Provides responses for the XLS sheets data comparison.
 */
class CsvDataImportController extends ControllerBase {

  /**
   * The _title_callback for the node.add route.
   *
   * @param \Drupal\node\NodeTypeInterface $node_type
   *   The current node.
   *
   * @return string
   *   The page title.
   */
  public function addPageTitle($type = NULL) {
    return $this->t('Import @name', ['@name' => $type]);
  }
}