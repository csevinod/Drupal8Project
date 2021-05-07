<?php

namespace Drupal\spreadsheet_importer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Archiver\Zip;
use Drupal\Core\Archiver\ArchiverException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Archiver\ArchiverManager;

class ImportexcelForm extends FormBase{

  protected $fileSystem;
  protected $pluginManagerArchiver;

  public function __construct(FileSystemInterface $file_system, ArchiverManager $plugin_manager_archiver) {
    $this->fileSystem = $file_system;
    $this->pluginManagerArchiver = $plugin_manager_archiver;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_system'),
      $container->get('plugin.manager.archiver')
    );
  }

  public function getFormId() {
    return 'spreadsheet_importer';
  }
    /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL) {

    $form['upload_file'] = [
      '#type' => 'managed_file',
      '#title' => t('Upload CSV File'),
      '#required' => TRUE,
      '#size' => 1000,
      '#description' => $this->t('Select zip files and please select start process to extract'),
      '#upload_validators' => [
        'file_validate_extensions' => ['zip'],
      ],
      '#upload_location' => 'public://content/zip_files/',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Start Process'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {

    $file = $form_state->getValue('upload_file');
    // $load_file = File::load($file[0]);
    // $load_file->setPermanent();
    // $load_file->save();

    // $fileUri = \Drupal::service('file_system')->realpath($load_file->getFileUri());
    // Archivers can only work on local paths.
    // $fileRealPath = $this->fileSystem->realpath($load_file->getFileUri());
    // $zip = $this->pluginManagerArchiver->getInstance(['filepath' => $fileUri]);
    // $zip->extract('public://content/csv_file');


      $zip = new Zip();
      $path='public://content/csv_file';
      $zip->extract($path, $file);
      $this->t('Extracted' . var_dump($zip->listContents()));

                  
       
    
//     catch (ArchiverException $exception) {
//       watchdog_exception('spreadsheet_importer', $exception);
//       // Some code if the error of unzip will happen.
     
//         $this->t('Zip file not extracted');
//      }

//     //  $open_file = fopen($load_file->getFileUri(), "r");
// }



//extract the zip files
    // $zipArchive = new ZipArchive();
    // $result = $zipArchive->open($file);
    // if ($result === TRUE) {
    //     $zipArchive ->extractTo('\Users\vieppa\Documents');
    //    $result= $zipArchive ->close();
    //     $this->t('success');
    // } else {
    //     // Do something on error
    //     $this->t('success');
    // }
  }
}