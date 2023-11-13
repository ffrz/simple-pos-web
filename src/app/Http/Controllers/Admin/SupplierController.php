<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;

class SupplierController extends PartyController
{
    protected $type = Party::TYPE_SUPPLIER;
    protected $view_path = 'admin.supplier';
    protected $index_url = 'admin/suppliers';

    public function view($id)
    {
        $data = Party::find($id);
        // $purchase_orders = $this->getStockUpdateModel()->getAllByPartyId($party->id);
        $purchase_orders = [];
        return view($this->view_path . '.view', compact('data', 'purchase_orders'));
    }
}
