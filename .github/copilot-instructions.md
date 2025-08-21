---
applyTo: "**"
---
# Project general coding standards

## Language information
- We are coding for PHP 8.2+ with CodeIgniter V4
- Stick as much as possible to the CodeIgniter V4 instructions and APIs
- Create custom PHP or JS functions only if strictly needed because nothing similar is already provided by CodeIgniter V4

## Naming Conventions
- Use PascalCase for component names, interfaces, and type aliases
- Use camelCase for variables, functions, and methods
- Prefix private class members with underscore (_)
- Use ALL_CAPS for constants

## Error Handling
- Use try/catch blocks for async operations
- Always log errors with contextual information

## Code styling
- Add blank line between different blocks of code to improve readability
- Add some comments to the code when some steps are not obvious or custom functions are used/created
- Do not add comments to simple operations that are easy to read

## Database data retrieval
- Always define a Model (if not already in the project) to read and write data to the database, for example:
```php
class BeaconModel extends Model
{
    protected $table         = 'bs_beacon';
    protected $primaryKey    = 'id';

    // Rest of the variables here

    // Functions to retrieve data
    public function getBeaconsByBand($band)
    {
        return $this->where('band', $band)
                    ->orderBy('callsign', 'ASC')
                    ->findAll();
    }
    public function getConfirmedBeaconsByBand($band)
    {
        return $this->where(['band' => $band, 'confirmed' => 1])
                    ->orderBy('callsign', 'ASC')
                    ->findAll();
    }

    public function getUnconfirmedBeaconsByBand($band)
    {
        return $this->where(['band' => $band, 'confirmed' => 0])
                    ->orderBy('callsign', 'ASC')
                    ->findAll();
    }
}
```
- Always use a Model to read and write data to the databse, for example:
```php
use App\Models\BeaconModel;
class Home extends BaseController
{
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
}
```

## Others
- When adding a component which needs a route, explictly specify that and include the route to configure
- When providing code snippets, always specify the path of the file where the code should be placed
- When creating new pages, it is preferred to create a dedicate Controllers and views to keep each component readable
