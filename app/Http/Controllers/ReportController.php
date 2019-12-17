<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmailCampaignsResource;
use App\Http\Resources\LeadReportResource;
use App\Http\Resources\ReportsResource;
use App\Http\Resources\TaskReportResource;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search');
        $query = company()->reports();
        if ($search) $query = $query->where('name', 'like', "%${search}%")->orWhere('description', 'like', "%${search}%");
        return ReportsResource::collection($query->paginate($perPage));
    }

    public function opportunity($report)
    {
        $query = company()->opportunities();
        if (!$report->filter['column'] == 'created_at') $query = $query->whereBetween(DB::raw('DATE(end_date)'), $report->filter['value']);
        else $query = $query->whereHas('customer', function (Builder $query) use ($report) {
            $query->whereBetween(DB::raw('DATE(created_at)'), $report->filter['value']);
        });
        $query = $query->with([
            'customer' => function ($query) {
                $query->select('id', 'code', 'name', 'email', 'phone_number', 'delivery_address', 'ownerable_id', 'ownerable_type')->with('ownerable:id,name');
            },
            'type',
            'source',
            'status',
        ])->get();
        $query = $query->each(function (&$item) {
            $item['order_name'] = $item['name'];
            $item->customer->owner = $item['customer']['ownerable']['name'];
            $customer = collect($item->customer)->toArray();
            foreach ($customer as $key => $val) {
                $item[$key] = $val;
            }
            $type = $item['type'] ? $item['type']['name'] : null;
            $source = $item['source'] ? $item['source']['name'] : null;
            $status = $item['status'] ? $item['status']['name'] : null;
            unset($item['type']);
            unset($item['source']);
            unset($item['status']);
            unset($item['ownerable']);
            $item['type'] = $type;
            $item['source'] = $source;
            $item['status'] = $status;
            unset($item['customer']);
        });
        $column = $report->column;
        return $query->map(function ($item) use ($column) {
            return collect($item)->only($column);
        });
    }
    public function task($report)
    {
        $query = company()->tasks()->whereBetween(DB::raw('DATE(start_date)'), $report->filter['value'])->with('user')->get()->each(function ($item) {
            $user = collect($item->user)->toArray();
            foreach ($user as $key => $val) {
                $item[$key] = $val;
            }
            $item["type"] = convertModelToType($item['taskable_type']);
            $item['status'] = $this->convertStatus($item['status']);
            unset($item['user']);
        });
        $column = $report->column;
        return $query->map(function ($item) use ($column) {
            return collect($item)->only($column);;
        });
    }
    private function convertStatus($val)
    {
        switch ($val) {
            case '1':
                return 'Chưa thực hiện';
                break;
            case '2':
                return 'Đang thực hiện';
                break;
            case '3':
                return 'Đã giải quyết';
                break;
            case '4':
                return 'Đã hoàn thành';
                break;
            default:
                return null;
                break;
        }
    }
    public function store(Request $request)
    {
        $report = company()->reports()->create($request->all());
        return created($report);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        if ($report->module == 'customer') {
            $data = $this->customer($report);
        } else if ($report->module == "task") {
            $data = $this->task($report);
        } else if ($report->module == 'opportunity')
            $data = $this->opportunity($report);
        return ['data' => $data, 'report' => $report];
    }

    private function customer($report)
    {
        $related = $report->related;
        if ($related) {
            if ($related == "order") {
                $query = company()->orders();
                if (!$report->filter['column'] == 'created_at') $query = $query->whereBetween(DB::raw('DATE(order_date)'), $report->filter['value']);
                else $query = $query->whereHas('customer', function (Builder $query) use ($report) {
                    $query->whereBetween(DB::raw('DATE(created_at)'), $report->filter['value']);
                });
                $query = $query->with([
                    'customer' => function ($query) {
                        $query->select('id', 'code', 'name', 'email', 'phone_number', 'delivery_address', 'ownerable_id', 'ownerable_type')->with('ownerable:id,name');
                    },
                    'products',
                    'invoice.bills',
                    'ownerable'
                ])->get();
                $query = $query->each(function ($item) {
                    $item['order_code'] = $item['code'];
                    $calculate = calculate(collect($item['products']), $item["shipping_fee"]);
                    $item['paid'] = collect($item['invoice']["bills"])->where('status', 'Đã xác nhận')->sum('payment_amount');
                    $item['total'] = $calculate['total'];
                    $item['discount'] = $calculate['discount'];
                    $item['seller'] = $item['ownerable']['name'];
                    $item['owed'] = $item['total'] - $item['paid'];
                    unset($item['code']);
                    $item->customer->owner = $item['customer']['ownerable']['name'];
                    $customer = collect($item->customer)->toArray();
                    foreach ($customer as $key => $val) {
                        $item[$key] = $val;
                    }
                    unset($item['products']);
                    unset($item['invoice']);
                    unset($item['customer']);
                    unset($item['ownerable']);
                })->map(function ($item) {
                    return collect($item)->only(
                        'order_code',
                        'seller',
                        'delivery_method',
                        'order_date',
                        'total',
                        'discount',
                        'paid',
                        'owed',
                        'code',
                        'name',
                        'owner',
                        'email',
                        'phone_number',
                        'delivery_address'
                    );
                });
            } else {
                $query = company()->quotes();
                if (!$report->filter['in_module']) $query = $query->whereBetween(DB::raw('DATE(quote_date)'), $report->filter['value']);
                else $query = $query->whereHas('customer', function (Builder $query) use ($report) {
                    $query->whereBetween(DB::raw('DATE(created_at)'), $report->filter['value']);
                });
                $query = $query->with([
                    'customer' => function ($query) {
                        $query->select('id', 'code', 'name', 'email', 'phone_number', 'delivery_address', 'ownerable_id', 'ownerable_type')->with('ownerable:id,name');
                    },
                ])->get();
                $query = $query->each(function ($item) {
                    $item['quote_code'] = $item['code'];
                    $calculate = calculate(collect($item['products']), $item["shipping_fee"]);
                    $item['total'] = $calculate['total'];
                    unset($item['code']);
                    $item->customer->owner = $item['customer']['ownerable']['name'];
                    $customer = collect($item->customer)->toArray();
                    foreach ($customer as $key => $val) {
                        $item[$key] = $val;
                    }
                    unset($item['products']);
                })->map(function ($item) {
                    return collect($item)->only(
                        'quote_code',
                        'quote_date',
                        'total',
                        'code',
                        'name',
                        'owner',
                        'email',
                        'phone_number',
                        'delivery_address'
                    );
                });
            }
        }
        $column = $report->column;
        return $query->map(function ($item) use ($column) {
            return collect($item)->only($column);
        });
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $report->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        delete($report);
    }

    public function getDebt(Request $request)
    {
        $orderDate = $request->query('orderDate');

        $query = company()->customers()->select('id', 'name', 'ownerable_type', 'ownerable_id')
            ->whereHas('orders', function (Builder $query) use ($orderDate) {
                $query->whereBetween('order_date', $orderDate);
            })->with('ownerable:id,name')->with(['orders' => function ($query) use ($orderDate) {
                $query->select('id', 'customer_id', 'shipping_fee')->whereBetween('order_date', $orderDate)->with('products:id', 'invoice:id,order_id', 'invoice.bills');
            }])->get();
        $query = $query->each(function ($item) {
            $item['owner'] = $item['ownerable']['name'];
            unset($item['ownerable']);
            $total = 0;
            $paid = 0;
            $item->orders->each(function ($item) use (&$total, &$paid) {
                $calculate = calculate($item->products, $item->shipping_fee);
                $paid += collect($item['invoice']['bills'])->where('status', 'Đã xác nhận')->sum('payment_amount');
                $total += $calculate['total'];
            });
            $item['total'] = $total;
            $item['paid'] = $paid;
            $item['rate'] = round($paid * 100 / $total, 2);
            $item['owed'] = $total - $paid;
            unset($item['orders']);
        });
        return ['data' => $query];
    }

    public function getProducts(Request $request)
    {
        $time = $request->query('time');
        $orders = $this->getFilter(company()->orders(), 'order_date', $time)->select('id')->get()->pluck('id')->all();
        return ['data' => DB::table('productables')->join('products', 'productables.product_id', '=', 'products.id')
            ->where('productable_type', 'App\Order')
            ->whereIn('productable_id', $orders)
            ->select(
                'product_id as id',
                'products.name',
                'products.code',
                DB::raw('SUM(quantity) as total_sales,SUM(quantity*price+quantity*price*(productables.tax/100)-quantity*price*(discount/100)) as revenue')
            )
            ->groupBy('product_id')->orderBy('total_sales', 'desc')->take(10)->get()];
    }
    public function getTasks(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $status = $request->query('status');
        $query = company()->tasks()->where('status', '<>', 4);
        $query = $query->where(function ($query) use ($status) {
            if ($status) $query = $query->where('status', $status);
        });
        return TaskReportResource::collection($query->with('taskable:id,name', 'user:id,name')->paginate($perPage));
    }
    public function getLeads()
    {
        return LeadReportResource::collection(
            company()
                ->leads()->where('converted', '<>', 0)
                ->select('id', 'name', 'email', 'company', 'phone_number', 'ownerable_type', 'ownerable_id')
                ->latest()->with('ownerable:id,name')->take(10)->get()
        );
    }
    public function getEmailCampaigns()
    {
        return EmailCampaignsResource::collection(company()->emailCampaigns()->latest()->with('email.related')->take(5)->get());
    }
    public function getRevenue(Request $request)
    {
        $time = $request->query('time');
        return ['data' => company()->users()->select('id', 'name')->with(['orders' => function ($query) use ($time) {
            $this->getFilter($query
                ->select('id', 'ownerable_type', 'ownerable_id', 'shipping_fee'), 'order_date', $time)
                ->with('products:id');
        }])->take(10)->get()->each(function ($item) {
            $total = 0;
            $item->orders->each(function ($item) use (&$total) {
                $calculate = calculate($item->products, $item->shipping_fee);
                $total += $calculate['total'];
            });
            $item['total'] = $total;
            unset($item['orders']);
        })];
    }
    private function getFilter($query, $column, $mode = null)
    {
        switch ($mode) {
            case 'today':
                return $query->whereDate($column, Carbon::now()->toDateString());
                break;
            case 'yesterday':
                return $query->whereDate($column, Carbon::parse('yesterday')->toDateString());
                break;
            case 'thisweek':
                return $query->whereBetween(DB::raw('DATE(created_at)'), $this->getWeek('this week'));
                break;
            case 'lastweek':
                return $query->whereBetween(DB::raw('DATE(created_at)'), $this->getWeek('last week'));
                break;
            case 'thismonth':
                return $query->whereMonth($column, Carbon::parse('this month')->month);
                break;
            case 'lastmonth':
                return $query->whereMonth($column, Carbon::parse('last month')->month);
                break;
            default:
                return $query;
                break;
        }
    }
    private function getWeek($time)
    {
        $week = Carbon::parse($time);
        return [$week->startOfWeek()->toDateString(), $week->endOfWeek()->toDateString()];
    }
    public function getConverted(Request $request)
    {
        $time = $request->query('time');
        $query = $this->getFilter(company()->leads(), 'created_at', $time);
        $newLeads = $query->count();
        $convertedLeads = $query->where('converted', 1)->count();
        $newOpportunities = company()->customers()->whereIn('converted_from', $query->select('id')->get()->pluck('id')->all())->has('opportunities')->count();
        return ['data' => [$newLeads, $convertedLeads, $newOpportunities]];
    }
}
