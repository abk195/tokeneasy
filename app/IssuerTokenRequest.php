<?php

namespace App;
use App\UserContract;

use Illuminate\Database\Eloquent\Model;

class IssuerTokenRequest extends Model
{
    /**
     * token_deploy_status is a 0/1 flag. Casting it keeps reads integer-typed
     * whatever the driver hands back, so comparisons like
     * `$request->token_deploy_status === 1` behave.
     */
    protected $casts = [
        'token_deploy_status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }   

    public function propertydetails()
    {
        return $this->hasMany('App\PropertyDetails','token_request_id','id');
    }

    
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function usercontract(){
        return $this->hasOne(UserContract::class, 'property_id', 'property_id');
    }

    public function blockchain()
    {
        return $this->belongsTo(BlockchainModel::class, 'blockchain_id');
    }

}
