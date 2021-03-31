<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\Record;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Contact;
use App\CustomerGroup;
use App\DeliveryPerson;
use App\Unit;
use App\User;
use App\Utils\ModuleUtil;
use App\Utils\RecordUtil;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{
    
    protected $moduleUtil;
    protected $recordUtil;

    public function __construct( ModuleUtil $moduleUtil,RecordUtil $recordUtil)
    {
        $this->moduleUtil = $moduleUtil;
        $this->recordUtil = $recordUtil;
    }

    public function index(Request $request)
    {
        if (!(auth()->user()->can('record.view') || auth()->user()->can('record.view_own'))) {
            abort(403, 'Unauthorized action.');
        }
      
        $business_id = request()->session()->get('user.business_id');
        $sales_representative = User::forDropdown($business_id, false, false, true);
        
        if ($request->ajax()) {
            $records = $this->recordUtil->getListRecords($business_id);
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $records->whereIn('records.location_id', $permitted_locations);
            }

            if (!empty(request()->added_by)) {
                $records->where('records.created_by', request()->added_by);
            }

            if (!empty(request()->supplier_id)) {
                $records->where('records.contact_id', request()->supplier_id);
            }


            if (!empty(request()->location_id)) {
                $records->where('records.location_id', request()->location_id);
            }
            
          
            if (!empty($start_date) && !empty($end_date)) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $records->whereBetween('expected_collection_date', [$start_date, $end_date]);
            }
       
            return Datatables::of($records)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                        data-toggle="dropdown" aria-expanded="false">' .
                        __("messages.actions") .
                        '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';
                    if (auth()->user()->can("record.view")) {
                        $html .= '<li><a href="#" data-href="' . action('RecordController@view', [$row->id]) . '" class="btn-modal" data-container=".view_modal"><i class="fas fa-eye" aria-hidden="true"></i>' . __("messages.view") . '</a></li>';
                    }
                    if (auth()->user()->can('record.update')) {
                        $html .=  '<li><a href="' . action('RecordController@edit', [$row->id]) . '"><i class="fas fa-edit"></i> ' . __("messages.edit") . '</a></li>';
                    }
                    if (auth()->user()->can('record.delete')) {
                        $html .= '<li><a href="' . action('RecordController@destroy', [$row->id]) . '" class="delete-record"><i class="fas fa-trash"></i> ' . __("messages.delete") . '</a></li>';
                    }
                    $html .=  '</ul></div>';
                    return $html;
                })
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        } else {
            $sales_representative = User::forDropdown($business_id, false, false, true);
            $business_locations = BusinessLocation::forDropdown($business_id, false);
            return view('record.index')->with(compact('sales_representative','business_locations'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('record.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $units = Unit::forDropdown($business_id, true);
        $types = [];
        if (auth()->user()->can('supplier.create')) {
            $types['supplier'] = __('report.supplier');
        }
        if (auth()->user()->can('customer.create')) {
            $types['customer'] = __('report.customer');
        }
        if (auth()->user()->can('supplier.create') && auth()->user()->can('customer.create')) {
            $types['both'] = __('lang_v1.both_supplier_customer');
        }
        $customer_groups = CustomerGroup::forDropdown($business_id);
        //Get all business locations
        $business_locations = BusinessLocation::forDropdown($business_id, false, true);
        $bl_attributes = $business_locations['attributes'];
        $business_locations = $business_locations['locations'];

        $contact = Contact::where('type', 'supplier')->get();
        $delivery_people = DeliveryPerson::forDropdown();
        return view('record.create')->with(compact('contact','units','business_locations','bl_attributes','types','customer_groups','delivery_people'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!auth()->user()->can('record.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');
            $user_id = $request->session()->get('user.id');
            $record_data = $request->only(['contact_id','item','expected_collection_date','location','quantity','unit_id']);
            $record_data['created_by'] = $user_id;
            $record_data['business_id'] = $business_id;
            $record_data['location_id'] = $request->business_location_id;
            $record_data['expected_collection_date']=$this->moduleUtil->uf_date($record_data['expected_collection_date']);
     
        DB::beginTransaction();

        $record = Record::create($record_data);
        
        DB::commit();
        
        $output = ['success' => 1,
        'msg' => __('contact.record_added_success')
        ];
        } catch (\Exception $e) {
             DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
             $output = ['success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
            
        }
        return redirect('records')->with('status',$output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!(auth()->user()->can('record.view') || auth()->user()->can('record.view_own'))) {
            abort(403, 'Unauthorized action.');
        }
        $record=Record::findorfail($id);
        $contact=Contact::find($record->contact_id);
        $business_location=BusinessLocation::find($record->business_id);
        $unit=Unit::find($record->unit_id);
        $created_by=User::find($record->created_by);
        return view('record.detail',compact('record','contact',
            'business_location','unit','created_by'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('record.update')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business = Business::find($business_id);
        $units = Unit::forDropdown($business_id, true);
        $record = Record::where('business_id', $business_id)
        ->where('id', $id)
        ->with(
            'contact',
            'location',
            'unit',
            'record_staff'
        )
        ->first();
  
        
        return view('record.edit')->with(compact('record', 'business_locations','units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        if (!auth()->user()->can('record.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $record=Record::findOrFail($id);
            $record_data = $request->only(['item','expected_collection_date','location','quantity','unit_id']);

        $record_data['expected_collection_date']=$this->moduleUtil->uf_date($record_data['expected_collection_date']);

        DB::beginTransaction(); 

        $record = $record->update($record_data);
        
        DB::commit();
        
        $output = ['success' => 1,
        'msg' => __('contact.record_updated_success')
        ];
        } catch (\Exception $e) {
             DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
             $output = ['success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
           
        }
        return redirect('records')->with('status',$output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('record.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                DB::beginTransaction();
                $record = Record::findorfail($id);
                $record->delete();
                DB::commit();

                $output = ['success' => true,
                    'msg' => __("contact.deleted_success")
                ];

            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                  DB::rollBack();
                $output = ['success' => false,
                    'msg' => __("something went wrong")
                ];
            }
            return $output;
        }
    }

    public function getallsupplier(){
        if (request()->ajax()) {
            $term = request()->q;
            if (empty($term)) {
                return json_encode([]);
            }
            $user = Contact::where('type','supplier')->where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term .'%');
            })
                ->get();
            return json_encode($user);
        }
    }

    public function view($id){
        if (!(auth()->user()->can('record.view') || auth()->user()->can('record.view_own'))) {
            abort(403, 'Unauthorized action.');
        }
        $record=Record::findorfail($id);
        $contact=Contact::find($record->contact_id);
        $business_location=BusinessLocation::find($record->business_id);
        $unit=Unit::find($record->unit_id);
        $created_by=User::find($record->created_by);
        return view('record.partial.detail_module',compact('record','contact',
            'business_location','unit','created_by'));
    }
}
