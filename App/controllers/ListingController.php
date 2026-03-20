<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
  protected $db;

  public function __construct()
  {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Show all listings
   *
   * @return void
   */
  public function index()
  {
    $listings = $this->db->query("SELECT * FROM listings")->fetchAll();

    loadView('listings/index', [
      'listings' => $listings
    ]);
  }

  /**
   * Show the create listing form
   *
   * @return void
   */
  public function create()
  {
    loadView('listings/create');
  }

  /**
   * Show a single listing
   *
   * @param array $params
   * @return void
   */
  public function show($params)
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

    // Check if listing exists
    if (!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    loadView('listings/show', [
      'listing' => $listing
    ]);
  }

  /**
   * Store data in database
   * 
   * @return void
   */
  public function store()
  {
    $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];
    $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

    $newListingData['user_id'] = 1; // Assuming user_id is 1 for now, you can replace it with actual user ID from session

    $newListingData = array_map('sanitize', $newListingData);

    $requiredFields = ['title', 'description', 'email', 'salary', 'city', 'state'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if (empty($newListingData[$field]) || !Validation::validateString($newListingData[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (!empty($errors)) {
      // Reload view with errors
      loadView('listings/create', [
        'errors' => $errors,
        'listing' => $newListingData
      ]);
    } else {
      $fields = [];
      $values = [];
      // Submit data to database
      foreach ($newListingData as $field => $value) {
        $fields[] = $field;
        // Convert empty string to null
        if ($value === '') {
          $newListingData[$field] = null;
        }
        $values[] = ':' . $field;
      }
      $fields = implode(', ', $fields);
      $values = implode(', ', $values);

      $query = "INSERT INTO listings ($fields) VALUES ($values)";
      $this->db->query($query, $newListingData);

      // Redirect to listings page
      header('Location: /listings');
    }
  }

  /**
   * Delete a listing
   * 
   * @param array $params
   * @return void
   */
  public function destroy($params)
  {
    $id = $params['id'];

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

    // Check if listing exists
    if (!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    $this->db->query("DELETE FROM listings WHERE id = :id", $params);

    // Set flash message
    $_SESSION['success_message'] = 'Listing deleted successfully';

    redirect('/listings');
  }

  /**
   * Show the listing edit form
   *
   * @param array $params
   * @return void
   */
  public function edit($params)
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

    // Check if listing exists
    if (!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }


    loadView('listings/edit', [
      'listing' => $listing
    ]);
  }

  /**
   * Udate a listing
   * 
   * @param array $params
   * @return void
   */
  public function update($params)
  {

    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

    // Check if listing exists
    if (!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];
    $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

    $updateValues = array_map('sanitize', $updateValues);

    $requiredFields = ['title', 'description', 'email', 'salary', 'city', 'state'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if (empty($updateValues[$field]) || !Validation::validateString($updateValues[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (!empty($errors)) {
      loadView('listings/edit', [
        'errors' => $errors,
        'listing' => $listing
      ]);
      exit;
    } else {
      // Submit to database
      $updateFields = [];

      foreach (array_keys($updateValues) as $field) {
        $updateFields[] = "{$field} = :{$field}";
      }
      $updateFields = implode(', ', $updateFields);
      $updateValues['id'] = $id;

      $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";
      $this->db->query($updateQuery, $updateValues);

      $_SESSION['success_message'] = 'Listing updated successfully';

      redirect('/listings/' . $id);
    }
  }
}
