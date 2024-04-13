<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;

class BatchController extends Controller
{
    public function emailBatch()
    {
        $pageTitle                           = 'Manage Email Batch';
        $query                               = Batch::email()->orderBy('id','DESC');
        $batches                             = $query->paginate(getPaginate());
        return view('admin.batch.email_batch', compact('pageTitle', 'batches'));
    }
    public function smsBatch()
    {
        $pageTitle                           = 'Manage SMS Batch';
        $query                               = Batch::sms()->orderBy('id','DESC');
        $batches                             = $query->paginate(getPaginate());
        return view('admin.batch.sms_batch', compact('pageTitle', 'batches'));
    }
}
