<?php

/**
 * Get The Base Path
 * 
 * @param string $path
 * 
 * @return string 
 */
function basePath($path = '')
{
  return __DIR__ . '/' . $path;
}

/**
 * Load a View
 * 
 * @param string $name
 * @return void
 */
function loadView($name, $data = [])
{
  $loadPath = basePath('App/views/' . $name . '.view.php');

  if (file_exists($loadPath)) {
    extract($data);
    require $loadPath;
  } else {
    die('View' . $name . 'Not Found');
  }
}

/**
 * Load a Partial
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name,)
{
  $partialPath = basePath('App/views/partials/' . $name . '.php');

  if (file_exists($partialPath)) {
    require $partialPath;
  } else {
    die('Partial' . $name . 'Not Found');
  }
}

/**
 * Inspect a value(s)
 * 
 * @param mixed $value
 * @return void
 */
function inspect($value)
{
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
}

/**
 * Inspect a value(s) and die
 * 
 * @param mixed $value
 * @return void
 */
function inspectAndDie($value)
{
  echo '<pre>';
  die(var_dump($value));
  echo '</pre>';
}

/**
 * Format salary
 * 
 * @param string $salary
 * @return string Formatted Salary
 */
function formatSalary($salary)
{
  return '$' . number_format(floatval($salary), 0, '.', ',');
}

/**
 * Sanitize Data
 * 
 * @param string $dirtyData
 * @return string
 */
function sanitize($dirtyData)
{
  return filter_var(trim($dirtyData), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given url
 * 
 * @param string $url
 * @return void
 */
function redirect($url)
{
  header('Location: {$url}');
  exit;
}
