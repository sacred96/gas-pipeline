<?php

function calculateLocation(array $houses)
{
    $count = count($houses);

    if ($count % 2 === 1) {
        return quickSelect($houses, intdiv($count, 2));
    }

    return 0.5 *
        (
            quickSelect($houses, $count / 2 - 1) +
            quickSelect($houses, $count / 2)
        );
}

function quickSelect(array $houses, int $index)
{
    if (count($houses) === 1) {
        if ($index !== 0) {
            throw new DomainException("Index can't be greater than zero for one element in set");
        }

        return $houses[0];
    }

    $pivot = pickPivot($houses);

    $lows = [];
    $highs = [];
    $pivots = [];
    foreach ($houses as $house) {
        switch ($house <=> $pivot) {
            case -1:
                $lows[] = $house;
                break;

            case 0:
                $pivots[] = $house;
                break;

            case 1:
                $highs[] = $house;
                break;
        }
    }

    if ($index < count($lows)) {
        return quickselect($lows, $index);
    }

    if ($index < count($lows) + count($pivots)) {
        return $pivots[0];
    }

    $newIndex = $index - count($lows) - count($pivots);

    return quickselect($highs, $newIndex);
}

function pickPivot(array $houses)
{
    if (count($houses) === 0) {
        throw new DomainException("Array can't be empty");
    }

    if (count($houses) < 5) {
        return nLogNMedian($houses);
    }

    $chunks = array_chunk($houses, 5);

    $medians = [];
    foreach ($chunks as $chunk) {
        if (count($chunk) === 5) {
            sort($chunk);
            $medians[] = $chunk[2];
        }
    }

    return calculateLocation($medians);
}

function nLogNMedian(array $houses)
{
    sort($houses);
    $count = count($houses);

    if ($count % 2 === 1) {
        return $houses[$count / 2];
    }

    return 0.5 * ($houses[$count / 2 - 1] + $houses[$count / 2]);
}


$houseSets = [
    [10, 1, 9, 2, 7, 4, 5, 8, 3, 0, 6],
    [1, 2, 3, 4, 5, 1000, 8, 9, 99],
    [1, 2, 3, 4, 5, 6],
    [0, 0, 0, 5, 5, 5],
    [5, 3, 1, 5372245, 322],
];

foreach ($houseSets as $houses) {
    $distances = implode(', ', $houses);
    $location = calculateLocation($houses);

    echo sprintf('For set of distances: [%s] best location is "%s"<br>', $distances, $location);
}
