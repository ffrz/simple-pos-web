<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;

class CustomerController extends PartyController
{
    protected $type = Party::TYPE_CUSTOMER;
    protected $view_path = 'admin.customer';
    protected $index_url = 'admin/customers';

    public function view($id)
    {
        $data = Party::find($id);
        // $sales_orders = $this->getStockUpdateModel()->getAllByPartyId($party->id);
        $sales_orders = [];
        return view($this->view_path . '.view', compact('data', 'sales_orders'));
    }
}
