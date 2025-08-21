<?php

namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\BeaconModel;

class Reports extends BaseController
{
    /**
     * Display a list of the most recent reports
     *
     * @return string
     */
    public function index()
    {
        $reportModel = new ReportModel();
        $beaconModel = new BeaconModel();
        
        // Get recent reports (default 50)
        $reports = $reportModel->getRecentReports(50);
        
        // Enhance report data with beacon information
        foreach ($reports as &$report) {
            // Get beacon info for each report
            $beacon = $beaconModel->find($report['beacon_id']);
            if ($beacon) {
                $report['beacon_callsign'] = $beacon['callsign'];
                $report['beacon_qrg'] = $beacon['qrg'];
                $report['beacon_band'] = $beacon['band'];
            } else {
                $report['beacon_callsign'] = 'Unknown';
                $report['beacon_qrg'] = 'N/A';
                $report['beacon_band'] = 'N/A';
            }
        }
        
        $data = [
            'title' => 'Recent Reception Reports',
            'reports' => $reports
        ];
        
        return view('layouts/header', ['title' => 'Recent Reception Reports']) .
               view('reports/index', $data) .
               view('layouts/footer');
    }
    
    /**
     * Add a new report for a specific beacon
     * 
     * @param int $beaconId Beacon ID to create report for
     * @return string|object View or redirect
     */
    public function add($beaconId = null)
    {
        if ($beaconId === null) {
            return redirect()->to('/');
        }
        
        $beaconModel = new BeaconModel();
        $reportModel = new ReportModel();
        
        // Get beacon data
        $beacon = $beaconModel->find($beaconId);
        
        if ($beacon === null) {
            return view('errors/html/error_404', [
                'message' => 'Beacon with ID '.$beaconId.' was not found.'
            ]);
        }
        
        // Handle form submission
        if ($this->request->getMethod() === 'post') {
            // Prepare data for validation and insertion
            $reportData = [
                'beacon_id' => $beaconId,
                'date' => date('Y-m-d H:i:s'),
                'callsign' => $this->request->getPost('callsign'),
                'locator' => strtoupper($this->request->getPost('locator')),
                'status' => $this->request->getPost('status'),
                'antenna' => $this->request->getPost('antenna'),
                'note' => $this->request->getPost('note')
            ];
            
            // Attempt to save the report
            if ($reportModel->insert($reportData)) {
                session()->setFlashdata('message', 'Report added successfully!');
                session()->setFlashdata('message_type', 'success');
                return redirect()->to('beacons/view/' . $beaconId);
            } else {
                // If validation fails
                session()->setFlashdata('message', 'Failed to add report. Please check your input.');
                session()->setFlashdata('message_type', 'danger');
                session()->setFlashdata('validation', $reportModel->errors());
                return redirect()->back()->withInput();
            }
        }
        
        // Display the form
        $data = [
            'title' => 'Add Report for ' . $beacon['callsign'],
            'beacon' => $beacon
        ];
        
        return view('layouts/header', ['title' => 'Add Report']) .
               view('reports/add', $data) .
               view('layouts/footer');
    }
}