<?php

namespace Drupal\csvdata_importer\Services;

use Drupal\node\Entity\Node;
use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\user\Entity\User;
use Drupal\paragraphs\Entity\Paragraph;

class CsvDataServices{

protected $output;
protected $type;
protected $context;

    public function __construct(array $output, $type, array &$context){
        $this->output =  $output;
        $this->type =  $type;
        $this->context = $context;
    }

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


  public function importCsvAccounts(array $output_data, $types, array &$context ){   

        $this->output =  $output_data;
        $this->type =  $types;
        $this->context = $context;

    \Drupal::logger('csvdata_importer')->notice('<pre><code>' . print_r($output_data, TRUE) . '</code></pre>' );

    switch($this->type){
      case 'aielead':  
        return self::csvImportLead($this->output, $this->context);
        break;

      case 'aieexpert':
        return self::csvImportExpert($this->output, $this->context);
        break;
          
      case 'a':
       return self::csvImportAccount($this->output, $this->context);
       break;
     }
  }

  public  function csvImportLead(array $output_data, array &$context) {
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
    
}

$item = new CsvDataServices('vinod','aielead','title');