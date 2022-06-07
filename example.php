<?php
require_once('./Antares.php');

Antares::init([
  "PLATFORM_URL" => 'http://localhost:3030', // TODO: Change this to your platform URL
  "ACCESS_KEY" => '', // TODO: Change this to your access key
  "cseId" => '', // TODO: Change this to your platform cse id, eg: "in-cse"
  "cseName" => '', // TODO: Change this to your platform cse name, eg: "cse-name"
]);
try {
  // RETRIEVE DATA
  echo "============================ Retrieve data =================================\n";
  // get application
  $resp = Antares::getInstance()->get(''); // TODO: Change this to your application uri eg: /in-cse/cse-name/SampleAPP
  if ($resp instanceof AE) {
    echo "AE: " . $resp->getName() . "\n";
    
    // list all application's devices
    $cntUris = $resp->listContainerUris();
    echo "Containers: " . count($cntUris) . "\n";
    
    foreach ($cntUris as $cntUri) {
      echo "  " . $cntUri . "\n";

      // get device
      $cnt = Antares::getInstance()->get($cntUri);
      echo "    " . $cnt->getName() . "\n";

      try {
        // get latest data
        $la = $cnt->getLatestContentInstace();
        echo "      [$la->ct]:$la->rn $la->con\n";
      } catch (Exception $e) {
        echo "      last data: " . $e->getMessage() . "\n";
      }
    }
  }

  // DISCOVERY LIMIT AND OFFSET
  echo "\n\n";
  echo "============================ Discovery =================================\n";
  $cnt = Antares::getInstance()->get(''); // TODO: Change this to your container uri, eg: /in-cse/cse-name/SampleAPP/sample-container
  $first10 = $cnt->listContentInstanceUris(10);
  
  // print first10
  foreach ($first10 as $uri) {
    echo $uri . "\n";
  }
  echo "==============================\n";
  $next10 = $cnt->listContentInstanceUris(10, count($first10));
  // print next10
  foreach ($next10 as $uri) {
    echo $uri . "\n";
  }
  
  $inc = 1;
  echo "==============================\n";
  $next10 = $cnt->listContentInstanceUris(10, count($first10) * $inc++ + count($next10));
  // print next10
  foreach ($next10 as $uri) {
    echo $uri . "\n";
  }

  echo "==============================\n";
  $next10 = $cnt->listContentInstanceUris(10, count($first10) * $inc++ + count($next10));
  // print next10
  foreach ($next10 as $uri) {
    echo $uri . "\n";
  }
  
  echo "==============================\n";
  $next10 = $cnt->listContentInstanceUris(10, count($first10) * $inc++ + count($next10));
  // print next10
  foreach ($next10 as $uri) {
    echo $uri . "\n";
  }

  // INSERT DATA
  echo "\n\n";
  echo "============================ Insert data =================================\n";
  try {
    $cnt = Antares::getInstance()->get(''); // TODO: Change this to your container uri, eg: /in-cse/cse-name/SampleAPP/sample-container
    $lastCin = $cnt->getLatestContentInstace();
    echo "Last CIN: [$lastCin->ct]:$lastCin->rn $lastCin->con\n";
  } catch (Exception $e) {
    echo "Last CIN: " . $e->getMessage() . "\n";
  }
  try {
    $cnt->insertContentInstance('{"help":2.0}', 'application/json');
    $lastCin = $cnt->getLatestContentInstace();
    echo "Current Last CIN: [$lastCin->ct]:$lastCin->rn $lastCin->con\n";
  } catch (Exception $e) {
    echo "Inserting CIN: " . $e->getMessage() . "\n";
  }

  // DELETE DATA
  echo "\n\n";
  echo "============================ Delete data =================================\n";
  try {
    $cnt = Antares::getInstance()->get(''); // TODO: Change this to your container uri, eg: /in-cse/cse-name/SampleAPP/sample-container
    $oldestCin = $cnt->getOldestContentInstance();
    echo "Delete oldest content instance: [$oldestCin->ct]:$oldestCin->rn $oldestCin->con\n";
    $oldestCin->delete();
  } catch (Exception $e) {
    echo "Deleting oldest CIN: " . $e->getMessage() . "\n";
  }
  try {
    $currentOldestCin = $cnt->getOldestContentInstance();
    echo  "Current Oldest content instance:   [$currentOldestCin->ct]:$currentOldestCin->rn $currentOldestCin->con\n";
  } catch (Exception $e) {
    echo "Current Oldest CIN: " . $e->getMessage() . "\n";
  }

} catch (Exception $e) {
  echo($e->getMessage());
}

