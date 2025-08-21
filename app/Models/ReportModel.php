<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table         = 'bs_report';
    protected $primaryKey    = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    
    // Fields that can be set during save, insert, or update
    protected $allowedFields = [
        'date', 
        'beacon_id', 
        'callsign', 
        'locator', 
        'status', 
        'antenna', 
        'note'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date'; // Using the date field as created timestamp

    // Validation
    protected $validationRules = [
        'beacon_id' => 'required|integer|greater_than[0]',
        'callsign'  => 'required|max_length[10]',
        'locator'   => 'required|max_length[6]',
        'status'    => 'required|integer|in_list[0,1]',
        'antenna'   => 'required|max_length[15]',
        'note'      => 'permit_empty|max_length[100]',
    ];
    
    protected $validationMessages = [
        'beacon_id' => [
            'required'      => 'Beacon ID is required',
            'integer'       => 'Beacon ID must be an integer',
            'greater_than'  => 'Beacon ID must be greater than zero',
        ],
        'callsign' => [
            'required'   => 'Callsign is required',
            'max_length' => 'Callsign cannot exceed 10 characters',
        ],
        'locator' => [
            'required'   => 'Locator is required',
            'max_length' => 'Locator cannot exceed 6 characters',
        ],
        'status' => [
            'required' => 'Status is required',
            'integer'  => 'Status must be an integer',
            'in_list'  => 'Status must be either 0 or 1',
        ],
        'antenna' => [
            'required'   => 'Antenna is required',
            'max_length' => 'Antenna cannot exceed 15 characters',
        ],
        'note' => [
            'max_length' => 'Note cannot exceed 100 characters',
        ],
    ];
    
    protected $skipValidation = false;

    /**
     * Get reports by beacon ID
     * 
     * @param int $beaconId The ID of the beacon
     * @return array Array of reports for the specified beacon
     */
    public function getReportsByBeacon(int $beaconId)
    {
        return $this->where('beacon_id', $beaconId)
                    ->orderBy('date', 'DESC')
                    ->findAll();
    }

    /**
     * Get reports by reporter callsign
     * 
     * @param string $callsign The callsign of the reporter
     * @return array Array of reports made by the specified callsign
     */
    public function getReportsByCallsign(string $callsign)
    {
        return $this->where('callsign', $callsign)
                    ->orderBy('date', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent reports with optional limit
     * 
     * @param int $limit Number of reports to return (default 10)
     * @return array Array of recent reports
     */
    public function getRecentReports(int $limit = 10)
    {
        return $this->orderBy('date', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get reports within a date range
     * 
     * @param string $startDate Start date in Y-m-d H:i:s format
     * @param string $endDate End date in Y-m-d H:i:s format
     * @return array Array of reports within the date range
     */
    public function getReportsByDateRange(string $startDate, string $endDate)
    {
        return $this->where('date >=', $startDate)
                    ->where('date <=', $endDate)
                    ->orderBy('date', 'DESC')
                    ->findAll();
    }
}