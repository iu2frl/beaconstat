<?php

namespace App\Controllers;

use App\Models\BeaconModel;

class Map extends BaseController
{
    /**
     * Display the map of all beacons
     */
    public function index(): string
    {
        $model = new BeaconModel();
        $allBeacons = $model->findAll(); // Fetch all beacons regardless of status
        
        // Get unique bands for color coding and legend
        $bands = [];
        foreach ($allBeacons as $beacon) {
            if (!empty($beacon['band']) && !in_array($beacon['band'], $bands)) {
                $bands[] = $beacon['band'];
            }
        }
        
        // Sort bands numerically
        sort($bands, SORT_NUMERIC);
        
        // Assign colors to bands
        $bandColors = [];
        $predefinedColors = [
            '50' => '#FF4136',      // Red
            '144' => '#0074D9',     // Blue
            '432' => '#2ECC40',     // Green
            '1296' => '#FF851B',    // Orange
            '2320' => '#B10DC9',    // Purple
            '5760' => '#FFDC00',    // Yellow
            '10368' => '#39CCCC',   // Teal
            '24048' => '#F012BE',   // Magenta
            '47088' => '#01FF70',   // Lime
            '76032' => '#85144b',   // Maroon
        ];
        
        // Standard color palette for any additional bands
        $standardColors = [
            '#3D9970', '#7FDBFF', '#001f3f', '#111111', 
            '#AAAAAA', '#DDDDDD', '#7FFF00', '#FF69B4'
        ];
        
        $colorIndex = 0;
        foreach ($bands as $band) {
            // Use predefined color if available, otherwise use from standard palette
            if (isset($predefinedColors[$band])) {
                $bandColors[$band] = $predefinedColors[$band];
            } else {
                $bandColors[$band] = $standardColors[$colorIndex % count($standardColors)];
                $colorIndex++;
            }
        }
        
        // Prepare data for the view
        $data = [
            'title' => lang('App.beacon_map'),
            'beacons' => $allBeacons,
            'bands' => $bands,
            'bandColors' => $bandColors
        ];
        
        return view('layouts/header') . 
                view('map/index', $data) .
                view('layouts/footer');
    }
}