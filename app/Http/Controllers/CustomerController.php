<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\Party;
use App\Models\ServiceOrder;
use App\Models\StockUpdate;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        ensure_user_can_access(AclResource::CUSTOMER_LIST);

        $filter = [
            'active' => (int)$request->get('active', 1),
            'search' => $request->get('search', ''),
        ];

        $q = Customer::query();
        $q->where('type', '=', Party::TYPE_CUSTOMER);

        if ($filter['active'] != -1) {
            $q->where('active', '=', $filter['active']);
        }

        if (!empty($filter['search'])) {
            $q->where('id2', '=', $filter['search']);
            $q->orWhere('name', 'like', '%' . $filter['search'] . '%');
            $q->orWhere('phone', 'like', '%' . $filter['search'] . '%');
            $q->orWhere('address', 'like', '%' . $filter['search'] . '%');
        }

        $items = $q->orderBy('name', 'asc')->paginate(10);
        return view('admin.customer.index', compact('items', 'filter'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            ensure_user_can_access(AclResource::ADD_CUSTOMER);
            $item = new Customer();
            $item->id2 = Party::getNextId2(Party::TYPE_CUSTOMER);
            $item->active = true;
        } else {
            ensure_user_can_access(AclResource::EDIT_CUSTOMER);
            $item = Customer::find($id);
            if (!$item) {
                return redirect('admin/customer')->with('warning', 'Pelanggan tidak ditemukan.');
            }
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100',
            ], [
                'name.required' => 'Nama pelanggan harus diisi.',
                'name.max' => 'Nama pelanggan terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];

            $tmpData = $request->all();
            if (empty($tmpData['active']))
                $tmpData['active'] = false;

            $item->fill($tmpData);
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::CUSTOMER_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Pelanggan',
                'Pelanggan ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/customer')->with('info', 'Pelanggan telah disimpan.');
        }

        return view('admin.customer.edit', compact('item'));
    }

    public function delete($id)
    {
        ensure_user_can_access(AclResource::DELETE_CUSTOMER);

        $item = Customer::findOrFail($id);
        $orderCount = DB::select("select count(0) as count from service_orders where customer_id=:id", [":id" => $item->id])[0]->count;
        $serviceOrderCount = DB::select("select count(0) as count from stock_updates where party_id=:id", [":id" => $item->id])[0]->count;

        DB::beginTransaction();
        if ($orderCount > 0 || $serviceOrderCount > 0) {
            $item->active = false;
            $item->save();
            $message = 'Pelanggan ' . e($item->name) . ' telah dinonaktifkan.';
        }
        else {
            $item->delete();
            $message = 'Pelanggan ' . e($item->name) . ' telah dihapus.';
        }

        UserActivity::log(
            UserActivity::CUSTOMER_MANAGEMENT,
            'Hapus Pelanggan',
            $message,
            $item->toArray()
        );
        DB::commit();

        return redirect('admin/customer')->with('info', $message);
    }

    public function detail(Request $request, $id)
    {
        $item = Customer::findOrFail($id);

        $item->total_sales = DB::select('select ifnull(abs(sum(total)), 0) as sum from stock_updates where type=:type and status=:status and party_id=:party_id', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'party_id' => $item->id,
        ])[0]->sum;

        $item->sales_count = DB::select('select ifnull(count(0), 0) as count from stock_updates where type=:type and status=:status and party_id=:party_id', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'party_id' => $item->id,
        ])[0]->count;

        $item->total_profit = DB::select('select ifnull(abs(sum(total-total_cost)), 0) as sum from stock_updates where type=:type and status=:status and party_id=:party_id', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'party_id' => $item->id,
        ])[0]->sum;

        $item->total_receivable = DB::select('select ifnull(abs(sum(total_receivable)), 0) as sum from stock_updates where type=:type and status=:status and party_id=:party_id', [
            'type' => StockUpdate::TYPE_SALES_ORDER,
            'status' => StockUpdate::STATUS_COMPLETED,
            'party_id' => $item->id,
        ])[0]->sum;

        $item->service_count = DB::select('select ifnull(count(0), 0) as count from service_orders where customer_id=:customer_id', [
            'customer_id' => $item->id,
        ])[0]->count;

        $sales = StockUpdate::where('party_id', '=', $item->id)
            ->where('status', '<>', StockUpdate::STATUS_OPEN)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $services = ServiceOrder::where('customer_id', '=', $item->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.customer.detail', compact('item', 'sales', 'services'));
    }
}
