<?php

namespace Drupal\Csvdata_importer\Repository;

use Drupal\node\Entity\Node;
use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\user\Entity\User;
use Drupal\paragraphs\Entity\Paragraph;

class CsvDataImportRepository {

// protected $data=[];
// protected $types;
// protected $contexts=[];

//   public function __construct(array $output_data, $type, array &$context){
//       $this->data = $output_data;
//       $this->types = $type;
//       $this->contexts = $context;
//   }

    /**
     * Perform tasks when a batch is complete.
     *
     *   Return message of success or error.
     *
     * @param bool $success
     *   A boolean indicating whether the batch operation successfully concluded.
     * @param int $results
     *   The results from the batch process.
   */
    public static function csvImportFinished($success, $results) {

        if ($success) {
          $message = \Drupal::translation()->formatPlural(
            count($results),
            'One Account content imported.', '@count contents imported.'
          );
        }
        else {
          $message = t('Finished with error.');
        }
        // Issue related to by-passing the class instantiation.
        // So using static function call and not via dependency.
        \Drupal::messenger()->addMessage($message);
      }
  /**
     * Batch callback for Import content from csv file.
     *
     * @param array $output_data
     *   Data which is present in the Csv file.
     * @param string $type
     *   That will contain type of content 
     * @param array $context
     *   That will contain information about the
     *   status of the batch. The values in $context will retain their
     *   values as the batch progresses.
    */
   
    public static function csvImportContents(array $output_data, $types, array &$context ){   

      \Drupal::logger('csvdata_importer')->notice('<pre><code>' . print_r($output_data, TRUE) . '</code></pre>' );
        // $type=NULL;
        // $data=NULL;

      // $output_data=['type'=>$type, 'data'=>$data];

      // print_r($output_data['type'] . '' . $output_data['data']);

      switch($type){
        case 'aielead':  
          print_r($type);
          return self::csvImportLead($output_data, $context);
          break;

        case 'aieexpert':
          print_r($output_data['type']);
          return self::csvImportExpert($output_data, $context);
          break;
            
        case 'a':
         return self::csvImportAccount($output_data, $context);
         break;
       }
    }

   /**
    * Function which will import AieLead content
    */
    public static function csvImportLead(array $output_data, array &$context) {
      \Drupal::logger('csvdata_importer')->notice('<pre><code>' . print_r($output_data, TRUE) . '</code></pre>' );
      $userId = \Drupal::currentUser()->id();
        $node = Node::create([
          'type' => 'aielead',
          'langcode' => 'en',
          'title' => $output_data[0],
          'uid' => $userId,
          'field_aielead_name' => $output_data[1],
          'field_aielead_email' => $output_data[2]
        ]);
        $node->save();
    $context['results'][] = $output_data['title'];
    $context['message'] = t('Created @title', array('@title' => $output_data['title']));
  } 
        
  /**
    * Function which will import AieExpert content
    */
    public static function csvImportExpert(array $output_data, array &$context) {
      \Drupal::logger('csvdata_importer')->notice('<pre><code>' . print_r($output_data, TRUE) . '</code></pre>' );
      $userId = \Drupal::currentUser()->id();
        $node = Node::create([
          'type' => 'aieexpert',
          'langcode' => 'en',
          'title' => $output_data[0],
          'uid' => $userId,
          'field_aieexpert_email' => $output_data[1],
          'field_aieexpert_name' => $output_data[2]
        ]);
        $node->save();
    $context['results'][] = $output_data['title'];
    $context['message'] = t('Created @title', array('@title' => $output_data['title']));
  } 

   /**
     * Get all nodes of a certain type
     * @param $type
     * @return array
     * 'a' is Account machine name
   */ 
  public static function getAccountAllNodes($type) {

    $nids = \Drupal::entityQuery('node')->condition('type','a')->execute();

    if (is_array($nids) && count($nids) > 0) {
      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
      return $nodes;
    }    
    return array();
  }
}

// $object = new CsvDataImportRepository($this->data, $this->types, $this->contexts);
// $object->csvImportContents($this->data, $this->types, $this->contexts);

  

