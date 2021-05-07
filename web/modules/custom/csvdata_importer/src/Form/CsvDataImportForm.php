<?php

namespace Drupal\csvdata_importer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;


class CsvDataImportForm extends FormBase{

  protected $currentUser;
  protected $nodeManager;
  // 
  // public function __construct( EntityTypeManager $entity_type_manager,AccountProxyInterface $current_user){
  //   $this->currentUser = $current_user;
  //   $this->nodeManager = $entity_type_manager->getStorage('node');
  // }

  /**
   * {@inheritdoc}
   */
  // public static function create(ContainerInterface $container) {
  //   return new static(
  //     $container->get('entity_type.manager'),
  //     $container->get('current_user')
  //   );
  // }

   /**
   * {@inheritdoc}
   */
   public function getFormId() {
    return 'csvdata_importer';
  }

   /**
   * {@inheritdoc}
   */
   public function buildForm(array $form, FormStateInterface $form_state, $type = NULL) {

    $form = [
      '#attributes' => [
        'enctype' => 'multipart/form-data',
      ],
    ];
    $form['upload_file'] = [
      '#type' => 'managed_file',
      '#title' => t('Upload CSV File'),
      '#required' => TRUE,
      '#size' => 1000,
      '#description' => $this->t('CSV with the Account information. Please click on "Start Import" to processing import.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#upload_location' => 'public://content/csv_file/',
    ];

    $form['type'] = [
      '#type' => 'hidden',
      '#value' => $type,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Start Import'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
   public function validateForm(array &$form, FormStateInterface $form_state) {

  }
    /**
   * {@inheritdoc}
   */
   public function submitForm(array &$form, FormStateInterface $form_state) {

    $type = $form_state->getValue('type');
    $file = $form_state->getValue('upload_file');
    $load_file = File::load($file[0]);
    $load_file->setPermanent();
    $load_file->save();

        // Open the uploaded csv file.
    // $open_file = fopen($load_file->getFileUri(), "r");
    $service = \Drupal::service('csvdata_importer.importcsv')->csvImportFinished();
    
    $batch = [
      'title' => $this->t('ACCOUNT IMPORT BATCH TITLE @type', ['@type' => $type]),
      'operations' => [],
      'finished' => $service,
      'init_message' => $this->t('ACCOUNT IMPORT BATCH INIT MESSAGE @type', ['@type' => $type]),
      'progress_message' => $this->t('ACCOUNT IMPORT BATCH PROGRESS MESSAGE @type', ['@type' => $type])
    ];

    $service1 = \Drupal::service('csvdata_importer.importcsv')->importCsvAccounts();
    $row = 1;
    if (($handle = fopen($load_file->getFileUri(), "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row > 1) {
          $batch['operations'][] = [$service1, [$data, $type]
        ];
        }
        $row++;
      }
      fclose($handle);
    }
    batch_set($batch);


//    $row = 1;
//    $data=[];
//     if (($handle = fopen($load_file->getFileUri(), "r")) !== FALSE) {
//       while (($data = fgetcsv($load_file, 1000, ",")) !== FALSE) {
//         $num = count($data);
//$service = \Drupal::service('custom_services_example.say_hello');
//          echo "<p> $num fields in line $row: <br /></p>\n";
//            $row++;
//           for ($c=0; $c < $num; $c++) {
//         // echo $data[$c] . "<br />\n";
//         print_r($data);
//         die();
//     }
//   }
//   fclose($handle);
// }
//'finished' => '\Drupal\csvdata_importer\Repository\CsvDataImportRepository::csvImportFinished',
//'\Drupal\csvdata_importer\Repository\CsvDataImportRepository::csvImportContents
    
  }
}


