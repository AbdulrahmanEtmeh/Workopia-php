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
function loadView($name)
{
  $loadPathe = basePath('views/' . $name . '.view.php');

  if (file_exists($loadPathe)) {
    return $loadPathe;
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
function loadPartial($name)
{
  $partialPath = basePath('views/partials/' . $name . '.php');

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
