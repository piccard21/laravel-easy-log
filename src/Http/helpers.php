<?php

/**
 * replace '_' with ' '
 *  
 * @param string $slug
 * @return string
 */
function str_spacecase($slug) {
    return str_replace("_", " ", $slug);
}

/**
 * shorten a string
 *  
 * @param string $slug
 * @return string
 */
function str_shorten($slug) {
    $length = config('laravel-easy-log.db.view.message_max_length', '250');
    if (strlen($slug) > $length) {
        return substr($slug, 0, $length) . " ...";
    }
    return $slug;
}

/**
 * wrapper for shorten & replace _ 
 *  
 * @param string $slug
 * @return string
 */
function prepareColumnHeading($slug) {
    return strtoupper(str_spacecase($slug));
}

/**
 * return a 'style: width:XX%' where XX is from laravel-easy-log.db.view.message_column_width
 *  
 * @param string $col
 * @return string
 */
function getColumnWidth($col) {
    if ($col == 'message') {
        return "style=width:" . config('laravel-easy-log.db.view.message_column_width', '50%');
    }
    return '';
}

function getLevels() {
    return [
        '100' => 'debug',
        '200' => 'info',
        '250' => 'notice',
        '300' => 'warning',
        '400' => 'error',
        '500' => 'critical',
        '550' => 'alert',
        '600' => 'emergency',
    ];
}

/**
 * wrapper for column-content output
 *  
 * @param string $col
 * @param string $slug
 * @return string
 */
function prepareOutput($col, $slug) {
    $levels = getLevels();
    if ($col == 'level') {
        $slug = $levels[$slug];
    }
    $slug = str_shorten($slug);
    return htmlspecialchars($slug);
}

/**
 * get class for level
 *  
 * @param string $level 
 * @return string
 */
function getLevelColumnClass($level) {
    $class = '';
    switch ($level) {
        case 200:
            $class = "info";
            break;
        case 250:
            $class = "success";
            break;
        case 300:
            $class = "warning";
            break;
        case 400:
            $class = "danger";
            break;
        case 500:
            $class = "danger";
            break;
        case 550:
            $class = "danger";
            break;
        case 600:
            $class = "danger";
            break;
    }

    return $class;
}

/**
 * get class: container or container-fluid
 * 
 * @return string bs-class
 */
function getContainerClass() {
    return (config('laravel-easy-log.db.view.big', false) ? 'container-fluid' : 'container');
}

/**
 * get options for result-number
 * 
 * @return string
 */
function getResultNumberOptions() {
    $resultNumbers = [10, 50, 100, 200, 300, 400, 500, 1000, 2000, 3000, 4000, 5000];

    array_merge($resultNumbers, [config('laravel-easy-log.db.view.result-number')]);
    $options = '';
    foreach ($resultNumbers as $value) { 
        $selected = ($value == session()->get('result-number', config('laravel-easy-log.db.view.result-number'))) ? 'selected' : '';
        $options .= '<option value="' . $value . '" ' . $selected . '>' . $value . '</option>';
    }
    echo $options;
}


/**
 * get options for result-sort-direction
 * 
 * @return string
 */
function getResultSortDirection() {         
    $options = '';

    foreach (["asc", "desc"] as $value) { 
        $selected = ($value == session()->get('result-sort-direction', "desc")) ? 'selected' : '';
        $options .= '<option value="' . $value . '" ' . $selected . '>' . $value . '</option>';
    }
    echo $options;
}


/**
 * get options for result-sort-direction
 * 
 * @param string $selection 
 * @return string
 */
function getResultLevelOptions($selection) {   
    $options = '';
    foreach (getLevels() as $value=>$level) { 
        $selected = in_array($value, session()->get('result-level',  [100, 200, 250, 300, 400, 500, 550, 600])) ? 'selected' : '';
        $options .= '<option value="' . $value . '" ' . $selected . '>' . $level . '</option>';
    }
    echo $options;
}
