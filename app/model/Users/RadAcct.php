<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;


class RadAcct extends Model
{
    //
    protected $connection = "mysql1";
    protected $table = "radacct";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'radacctid', 'acctsessionid', 'acctuniqueid', 'username', 'groupname', 'realm', 'nasipaddress', 'nasportid', 'nasporttype', 'acctstarttime', 'acctupdatetime', 'acctstoptime', 'acctinterval', 'acctsessiontime', 'acctauthentic', 'connectinfo_start', 'connectinfo_stop', 'acctinputoctets', 'acctoutputoctets', 'calledstationid', 'callingstationid', 'acctterminatecause', 'servicetype', 'framedprotocol', 'framedipaddress'

    ];

    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];





}
