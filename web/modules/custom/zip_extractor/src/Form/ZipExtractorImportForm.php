<?php

namespace Drupal\zip_extractor\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Archiver\Zip;
use Drupal\Core\Archiver\ArchiverException;

class ZipExtractorImportForm extends FormBase{

  public function getFormId() {
    return 'zip_extractor';
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
      '#description' => $this->t('CSV with the Muncipality information. Please click on "Start Import" to processing import.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['zip'],
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

  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
    $type = $form_state->getValue('type');
    $file = $form_state->getValue('upload_file');
    $load_file = File::load($file[0]);
    $load_file->setPermanent();
    $load_file->save();

    //open the zip files
    $zip = new ZipArchive;
    $res = $zip->open($load_file); 
    if ($res === TRUE) {
    $zip->extractTo('C:\xampp\htdocs\drupal_aid_composer');
    $zip->close();
    }
    $open_file = fopen($load_file->getFileUri(), "r");
}

}