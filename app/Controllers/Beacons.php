<?php

namespace App\Controllers;

use App\Models\BeaconModel;
use App\Models\ReportModel;

class Beacons extends BaseController
{    
    /**
     * Display beacon details by ID
     *
     * @param int $id Beacon ID
     * @return string
     */
    public function view($id = null)
    {
        if ($id === null) {
            return redirect()->to('/');
        }

        $beaconModel = new BeaconModel();
        $reportModel = new ReportModel();

        // Validate the ID
        if (!is_numeric($id) || $id <= 0) {
            return view('errors/html/error_404', [
                'message' => 'Invalid beacon ID provided.'
            ]);
        }
        
        // Get beacon data
        $beacon = $beaconModel->find($id);
        
        if ($beacon === null) {
            return view('errors/html/error_404', [
                'message' => 'Beacon with ID '.$id.' was not found.'
            ]);
        }
        
        // Get beacon reports
        $reports = $reportModel->getReportsByBeaconId($id);
            
        // Process report data for display if needed
        $data = [
            'title' => 'Beacon Details - ' . $beacon['callsign'],
            'beacon' => $beacon,
            'reports' => $reports
        ];
        
        return view('layouts/header', ['title' => $data['title']]) .
                view('beacons/beacon_details', $data) .
                view('layouts/footer');
    }
}