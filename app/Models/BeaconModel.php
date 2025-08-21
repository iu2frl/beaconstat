<?php

namespace App\Models;

use CodeIgniter\Model;

class BeaconModel extends Model
{
    protected $table         = 'bs_beacon';
    protected $primaryKey    = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    
    // Fields that can be set during save, insert, or update
    protected $allowedFields = [
        'callsign', 
        'locator', 
        'qrg', 
        'band', 
        'qth', 
        'asl', 
        'antenna', 
        'mode', 
        'qtf', 
        'power', 
        'status', 
        'confirmed'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'callsign' => 'required|max_length[10]',
        'locator'  => 'required|max_length[6]',
        'qrg'      => 'required|numeric',
        'band'     => 'required|integer',
        'qth'      => 'permit_empty|max_length[20]',
        'asl'      => 'permit_empty|integer|greater_than_equal_to[0]',
        'antenna'  => 'permit_empty|max_length[20]',
        'mode'     => 'permit_empty|max_length[10]',
        'qtf'      => 'permit_empty|max_length[10]',
        'power'    => 'permit_empty|numeric|greater_than_equal_to[0]',
        'status'   => 'permit_empty|integer|in_list[0,1]',
        'confirmed'=> 'permit_empty|integer|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'callsign' => [
            'required' => 'Callsign is required',
            'max_length' => 'Callsign cannot exceed 10 characters',
        ],
        'locator' => [
            'required' => 'Locator is required',
            'max_length' => 'Locator cannot exceed 6 characters',
        ],
        'qrg' => [
            'required' => 'QRG is required',
            'numeric' => 'QRG must be a number',
        ],
        'band' => [
            'required' => 'Band is required',
            'integer' => 'Band must be an integer',
        ],
    ];
    
    protected $skipValidation = false;

    /**
     * Get all active beacons
     * 
     * @return array Array of active beacons
     */
    public function getActiveBeacons()
    {
        return $this->where('status', 1)
                    ->orderBy('band', 'ASC')
                    ->orderBy('callsign', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get beacon by callsign
     * 
     * @param string $callsign The beacon callsign
     * @return array|null The beacon data or null if not found
     */
    public function getBeaconByCallsign($callsign)
    {
        return $this->where('callsign', $callsign)->first();
    }
    
    /**
     * Get beacons filtered by band
     * 
     * @param int $band The band to filter by
     * @return array Array of beacons in the specified band
     */
    public function getBeaconsByBand($band)
    {
        return $this->where('band', $band)
                    ->orderBy('callsign', 'ASC')
                    ->findAll();
    }
}