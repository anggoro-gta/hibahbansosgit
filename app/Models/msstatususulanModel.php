<?php

namespace App\Models;

use CodeIgniter\Model;

class msstatususulanModel extends Model
{
    protected $table = 'ms_status_usulan';
    protected $useTimestamps = true;
    protected $allowedFields = ['status'];
}
