<?php

namespace App\Controllers;

use App\Models\BeaconModel;

class Home extends BaseController
{
    public function pageContent($data): string
    {
        // This method can be used to render specific content based on the data provided
        // For now, it just returns a simple view with the data
        return view('layouts/header', ['title' => 'Homepage']) .
                view('beacons/beacons_per_band', $data) .
                view('layouts/footer');
    }

    public function index(): string
    {
        $model = new BeaconModel();
        $listOfBands = $model->getListOfBands();
        $confirmedBeacons = $model->getConfirmedBeaconsByBand("144");
        $unconfirmedBeacons = $model->getUnconfirmedBeaconsByBand("144");

        // Prepare data to send to the view
        $data = [
            'bandName' => '144',
            'confirmedBeacons' => $confirmedBeacons,
            'unconfirmedBeacons' => $unconfirmedBeacons,
            'listOfBands' => $listOfBands,
        ];

        // Return the beacons_per_band view with the data
        return $this->pageContent($data);
    }

    /**
     * Display beacons for a specific band
     * 
     * @return string
     */
    public function showBand(): string
    {
        // Get the band from the request or default to 144 MHz
        $band = $this->request->getGet('band') ?? '144';
        
        $model = new BeaconModel();
        $listOfBands = $model->getListOfBands();
        
        // Get confirmed and unconfirmed beacons for the selected band
        $confirmedBeacons = $model->getConfirmedBeaconsByBand($band);
        $unconfirmedBeacons = $model->getUnconfirmedBeaconsByBand($band);

        // Prepare data to send to the view
        $data = [
            'bandName' => $band,
            'confirmedBeacons' => $confirmedBeacons,
            'unconfirmedBeacons' => $unconfirmedBeacons,
            'listOfBands' => $listOfBands,
        ];

        // Return the beacons_per_band view with the data
        return $this->pageContent($data);
    }
}
