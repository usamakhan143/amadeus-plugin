<?php


function get_plugin_options_ap($name) {
    return carbon_get_theme_option($name);
}

function getIdAttributes() {
    $id_attributes = get_plugin_options_ap('id_attributes');
    if ($id_attributes) {
        $flattenedData = array();

        foreach ($id_attributes as $subArray) {
            foreach ($subArray as $value) {
                if ($value != '_') {
                    $flattenedData[] = '#' . trim($value);
                }
            }
        }

        $resultString = implode(', ', $flattenedData);
    }
    return $resultString;
}

function getIdAttributesForCitiesOnly() {
    $id_attributes = get_plugin_options_ap('id_attributes_for_cities');
    if ($id_attributes) {
        $flattenedData = array();

        foreach ($id_attributes as $subArray) {
            foreach ($subArray as $value) {
                if ($value != '_') {
                    $flattenedData[] = '#' . trim($value);
                }
            }
        }

        $resultString = implode(', ', $flattenedData);
    }
    return $resultString;
}