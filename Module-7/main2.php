<?php
error_reporting(0); // Чтобы не спамил ворнинги
$searchResult = [];
function search(string $searchRoot, string $searchName, &$searchResult)
{
    $list = scandir($searchRoot);
    $list = array_diff($list, ['.', '..']);
    foreach ($list as $listPart) {
        if (scandir($searchRoot . '/' . $listPart)) {
            search($searchRoot . '/' . $listPart, $searchName, $searchResult);
        } else {
            if ($listPart === $searchName) {
                $searchResult[] = $searchRoot . '/' . $searchName;
            }
        }
    }
}

search('/home/artem/0', '21000BKC28112_1100_-002.jpg', $searchResult);
if (isset($searchResult)) {
    print_r($searchResult);
} else {
    echo 'Nothing found', PHP_EOL;
}

echo PHP_EOL;
echo '            <------------------------------------------------>', PHP_EOL;
echo PHP_EOL;

$searchResult = [];
search('/home/artem/MINE/PhpstormProjects/php-developer-base/Module-7/test_search', 'test.txt', $searchResult);

if (isset($searchResult)) {
    print_r($searchResult);
} else {
    echo 'Nothing found', PHP_EOL;
}

echo PHP_EOL;
echo '            <------------------------------------------------>', PHP_EOL;
echo PHP_EOL;

foreach ($searchResult as $result) {
    if (filesize($result) === 0) {
        $searchResult = array_diff($searchResult, [$result]);
    }
}

var_dump($searchResult);