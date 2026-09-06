<?php

namespace App;

use App\UserContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

class Property extends Model
{
    use SoftDeletes;
    protected $fillable = [
       'user_id',
       'keystore_id',
       'propertyName',
       'propertyLocation',
       'propertyType',
       'totalDealSize',
       'dividend',
       'expectedIrr',
       'initialInvestment',
       'propertyEquityMultiple',
       'holdingPeriod',
       'total_sft',
       'propertyOverview',
       'propertyHighlights',
       'propertyLocationOverview',
       'propertyConnectivityOverview',
       'locality',
       'yearOfConstruction',
       'storeys',
       'propertyParking',
       'floorforSale',
       'propertyParkingRatio',
       'typicalFloorArea',
       'propertyFitouts',
       'propertyTotalBuildingArea',
       'propertyPowerBackup',
       'propertyForsaleCarpetArea',
       'propertyAirConditioning',
       'propertyDetailsOverview',
       'propertyDetailsHighlights',
       'propertyFloorPlan',
       'propertyLogo',
       'floorplan',
       'investor',
       'titlereport',
       'valuationreport',
       'termsheet',
       'feature',
       'status',
       'propertyVideo',
       'property_state',
       'token_type',
       'propertyManagementTeam',
       'propertyUpdatesDoc',
       'ManagementTeamDescription',
       'fundedMembers',
       'brochure',
       'airport',
       'hospitals',
       'fire_services',
       'slums',
       'industrial',
       'railway_tracks',
       'distance_fm_mainroad',
       'blockchain_id',
       'enable_internal_wallet'
    ];

    protected $dates = [
        'date'
    ];

    public function propertyImages()
    {
        return $this->hasMany('App\PropertyImage', 'property_id', 'id');
    }

    // public function propertyBulider()
    // {
    //     return $this->hasMany('App\PropertyBuilder', 'property_id', 'id');
    // }

    public function propertyComparable()
    {
        return $this->hasMany('App\PropertyComparable', 'property_id', 'id');
    }

    public function propertyLandmark()
    {
        return $this->hasMany('App\PropertyLandmark', 'property_id', 'id');
    }

    public function issuerToken()
    {
        return $this->belongsTo('App\IssuerTokenRequest', 'id', 'property_id');
    }

    public function members()
    {
        return $this->hasMany('App\ManagementMembers', 'property_id', 'id');
    }
    /**
     * Used to Get Token Details
     */
    public function userContract()
    {
        return $this->belongsTo('App\UserContract', 'id', 'property_id');
    }

    public function blockchain(){
        return $this->belongsTo('App\BlockchainModel', 'blockchain_id', 'id');
    }

    public function keystore()
    {
        return $this->belongsTo('App\KeystoreModel', 'keystore_id', 'id');
    }

    /**
     * Used to Get Updates
     */
    public function updates(){
        return $this->hasMany('App\PropertyUpdate', 'property_id', 'id')->orderBy('date', 'asc');
    }

    /**
     * Used to Get Property
     * {$feature} - Used to get Feature Property
     * {$id} - Used to get Particular Property Details
     */
    public function getProperty($feature = 0, $id = 0, $user_id = 0, $token_type = null)
    {
        $query = Property::where('status', '<>', 'pending')->whereNotIn('status',['block'])->orderBy('created_at', 'desc');

        if ($feature == 1) {
            $query->where('feature', 1);
        }

        if ($user_id > 0) {
            $query->where('user_id', $user_id);
        }

        if (isset($token_type)) {
            // Grouped: an ungrouped orWhereNull() escapes the status filters above
            // and pulls pending and blocked properties into the result.
            $query->where(function ($query) use ($token_type) {
                $query->where('token_type', $token_type);

                // Legacy rows predate token_type and are property tokens.
                if ($token_type == 1) {
                    $query->orWhereNull('token_type');
                }
            });
        }

        if ($id != 0)
            $property = $query->find($id);
        else
            $property = $query->get();
        // dd($property);
        return $property;
    }


    public function getTokenValueAttribute()
    {
        return IssuerTokenRequest::where('property_id', $this->id)->pluck('usdvalue')->first();
    }

    public function getTokenNameAttribute()
    {
        return IssuerTokenRequest::where('property_id', $this->id)->pluck('name')->first();
    }

    public function getIssuerDetails(){
        return User::with('identity')->where('id', $this->user_id)->first();
    }

    public function getPrivateKey($get = 'private'){
        $keystore = KeystoreModel::where('id', $this->keystore_id)
            ->where('user_id', $this->user_id)
            ->first();

        if (!$keystore) {
            throw new Exception("Keystore not found for property ID {$this->id}.");
        }

        $password = $keystore->getPassward();
        $data = [
            "filename" => $keystore->keystore_file_path,
            "password" => $password,
        ];

        $result = callNodeOperations('read', $data);

        if (!isset($result['status']) || $result['status'] !== 'success') {
            $errorMessage = $result['message'] ?? 'Unable to read keystore.';
            throw new Exception("Failed to retrieve key(s) for property ID {$this->id}. Reason: {$errorMessage}");
        }
        switch ($get) {
            case 'publicKey':
                return $keystore->public_address;

            case 'private':
                return $result['privatekey'];

            case 'both':
                return [
                    'publicKey' => $keystore->public_address,
                    'privatekey' => $result['privatekey'],
                ];

            default:
                throw new \Exception("Invalid key type requested: {$get}. Use 'public', 'private', or 'both'.");
        }
    }
}
