<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Office;
use App\Models\Remittance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateData as UpdateDataRequest;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class HomeController extends Controller
{
    public function getTable(Request $request) : Response
    {
        $filters = $request->all();
        $currentUser = $request->user();
        if ($request->has('dates_pay')){
            $filters['dates_pay'][0] = (int)$filters['dates_pay'][0];
            $filters['dates_pay'][1] = (int)$filters['dates_pay'][1];
        }
        $filterData = $this->getDefaultFilters($request);
        $clients = $this->getClients($request);
        $result = $this->getPreparedData($clients, $request);
        $resultWithPagination = $this->yourPaginator($result->toArray(), 25, $request);
        return Inertia::render('Table', [
            'offices' => (isset($filterData['offices'])) ? $filterData['offices'] : collect([]),
            'managers' => (isset($filterData['managers'])) ? $filterData['managers'] : collect([]),
            'lawyers' => (isset($filterData['lawyers'])) ? $filterData['lawyers'] : collect([]),
            'clients' => User::whereNotIn('id', [1,2,3,4])->select('id','first_name','last_name','middle_name')->get(),
            'payPlanItem' => $resultWithPagination,
            'filters' => $filters,
            'user' => $currentUser,
            'role' => $currentUser->roles->first()->name
        ]);
    }

    public function updateData(UpdateDataRequest $request) : RedirectResponse
    {
        $data = $request->validated();
        $user = User::findOrfail($data['id']);
        $user->update($data);
        return Redirect::back();
    }

    protected function getDefaultFilters(Request $request) : array
    {
        $user = $request->user();
        $dataFiler = [];
        $role = $user->roles->first()->name;
        switch ($role) {
            case 'Lawyer' :
                $lawyers = collect([]);
                $lawyers->put($user->id, $user->last_name . ' ' . $user->first_name);
                $dataFiler['lawyers'] = $lawyers;
                $dataFiler['managers'] = $this->getManagersList();
                $dataFiler['offices'] = $this->getSalesOffices([]);
                return $dataFiler;
            case 'Manager' :
                $office = $user->account()->first();
                $manager = collect([]);
                $manager->put($user->id, $user->last_name . ' ' . $user->first_name);
                $dataFiler['managers'] = $manager;
                $dataFiler['lawyers'] = $this->getLawyersList();
                $dataFiler['offices'] = $this->getSalesOffices([$office->office_id]);
                return $dataFiler;
        }
    }

    protected function getManagersList() : Collection
    {
        $managers = collect([]);
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'Manager');
        })->select('id', 'first_name', 'last_name')->get();
        foreach ($users as $item) {
            if (!empty($item->account)) {
                $managers->put($item->id, $item->last_name . ' ' . $item->first_name);
            }
        }
        return $managers;
    }

    protected function getLawyersList() : Collection
    {
        $lawyers = collect([]);
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'Lawyer');
        })->select('id', 'first_name', 'last_name')->get();
        foreach ($users as $item) {
            $lawyers->put($item->id, $item->last_name . ' ' . $item->first_name);
        }
        return $lawyers;
    }

    protected function getSalesOffices($id = []) : Collection
    {
        if (count($id) > 0) {
            return Office::whereIn('id', $id)->get()->pluck('name', 'id');
        } else {
            return Office::get()->pluck('name', 'id');
        }
    }

    protected function getClients(Request $request) : Collection
    {
        $currentUser = $request->user();
        $role = $currentUser->roles->first()->name;
        $clientId = collect([]);
        switch ($role) {
            case 'Lawyer' :
                $clientId = User::where('lawyer_id', $currentUser->id)->get()->pluck('id')->toArray();
            case 'Manager' :
                $clientId = User::where('manager_id', $currentUser->id)->get()->pluck('id')->toArray();
        }
        $allPayments = DB::table('remittances')
            ->join('users', 'remittances.user_id', '=', 'users.id')
            ->where('remittances.paid', 'Y')
            ->select(DB::raw('
                sum(remittances.amount) as sum_all_payment,
                remittances.user_id
            '))
            ->groupBy('remittances.user_id');
        $users = User::query()->with('remittances')
            ->leftJoin('users as lawyer', 'users.lawyer_id', '=', 'lawyer.id')
            ->leftJoin('users as manager', 'users.manager_id', '=', 'manager.id')
            ->leftJoin('remittances', 'remittances.user_id', '=', 'users.id')
            ->leftJoin('accounts', 'manager.id', '=', 'accounts.user_id')
            ->leftJoin('offices', 'offices.id', '=', 'accounts.office_id')
            ->leftJoinSub($allPayments, 'all_payments', function ($join) {
                $join->on('users.id', '=', 'all_payments.user_id');
            })
            ->where(function ($query) use ($request) {
                if ($request->has('dates_pay')) {
                    $requestDate = $request->input('dates_pay');
                    $query->whereBetween('users.first_date', [(int)$requestDate[0], (int)$requestDate[1]]);
                    $query->orWhereBetween('users.second_date', [(int)$requestDate[0], (int)$requestDate[1]]);
                }
            })
            ->when($clientId, function ($query) use ($clientId) {
                $query->whereIn('remittances.user_id', $clientId);
            })
            ->when($request->has('dates_transfer'), function ($query) use ($request) {
                $requestDate = $request->input('dates_transfer');
                $dateStart = Carbon::parse($requestDate[0])->format(('Y-m-d'));
                if (isset($requestDate[1])) {
                    $dateFinish = Carbon::parse($requestDate[1])->format(('Y-m-d'));
                } else {
                    $dateFinish = Carbon::now()->format(('Y-m-d'));
                }
                $query->whereBetween('profiles.client_handover_date', [$dateStart, $dateFinish]);
            })
            ->when($request->has('manager_id'), function ($query) use ($request) {
                $query->where('users.manager_id', $request->input('manager_id'));
            })
            ->when($request->has('client_id'), function ($query) use ($request) {
                $query->where('users.id', $request->input('client_id'))->first();
            })
            ->when($request->has('lawyer_id'), function ($query) use ($request) {
                $query->where('users.lawyer_id', $request->input('lawyer_id'));
            })
            ->when($request->has('office'), function ($query) use ($request) {
                $query->where('accounts.office_id', $request->input('office'));
            })
            ->where('users.price', '>', 0)
            ->where('users.plan', '>', 0)
            ->where('users.payment_per_month', '!=', '')
            ->orderBy('users.id', 'desc')
            ->select([
                'users.id', 'users.type', 'users.price', 'users.plan', 'users.first_date', 'users.second_date', 'users.payment_per_month',
                'users.first_name', 'users.last_name', 'users.date_registration', 'users.manager_id', 'users.middle_name', 'users.lawyer_id',
                'users.transfer_stop','lawyer.first_name AS lawyer_first_name', 'lawyer.last_name AS lawyer_last_name',
                'manager.first_name AS manager_first_name', 'manager.last_name AS manager_last_name', 'accounts.office_id',
                'offices.name','all_payments.sum_all_payment'
            ])
            ->get();
        return $users;
    }

    protected function getPreparedData(Collection $clients, Request $request) : Collection
    {
        $filters = $request->all();
        if (!isset($filters['date'])) {
            $filters['date'] = Carbon::now();
        } else {
            $filters['date'] = Carbon::parse($filters['date']);
        }
        $clientsId = $clients->pluck('id');
        $result = new Collection();
        $paymentMounth = Remittance::whereIn('user_id', $clientsId)
            ->where('paid', 'Y')
            ->whereBetween('payed_at', [$filters['date']->startOfMonth()->format('Y-m-d H:i:s'), $filters['date']->endOfMonth()->format('Y-m-d H:i:s')])
            ->get();
        foreach ($clients as $client) {
            $item = [];
            $item['id'] = $client->id;
            $item['date_register'] = Carbon::parse($client->date_registration)->format('d.m.Y');
            $item['name'] = $client->last_name . ' ' . $client->first_name . ' ' . $client->middle_name;
            $item['manager'] = [];
            $item['manager']['id'] = ($client->manager_id > 0) ? $client->manager_id : 0;
            $item['manager']['name'] = ($client->manager_id > 0) ? $client->manager_first_name . ' ' . $client->manager_last_name : '';
            $item['manager']['office'] = ($client->manager_id > 0) ? $client->name : '';
            $item['manager']['office_id'] = ($client->manager_id > 0) ? $client->office_id : 0;
            $item['lawyer'] = [];
            $item['lawyer']['id'] = ($client->lawyer_id > 0) ? $client->lawyer_id : 0;
            $item['lawyer']['name'] = ($client->lawyer_id > 0) ? $client->lawyer_first_name . ' ' . $client->lawyer_last_name : '';
            $item['program_type'] = $client->type;
            $item['program_price'] = $client->price;
            $item['program_plan'] = $client->plan;
            $item['program_first_date'] = $client->first_date;
            $item['program_second_date'] = $client->second_date;
            $item['program_payment_per_month'] = $client->payment_per_month;
            $item['client_handover_date'] = (isset($client->transfer_stop)) ? Carbon::parse($client->transfer_stop)->format('d.m.Y') : '';
            $userMonthPayment = $paymentMounth->where('user_id', $client->id);
            $item['payment'] = [];
            if ($userMonthPayment->isNotEmpty()) {
                foreach ($userMonthPayment as $payment) {
                    $item['payment'][$payment->id] = ['date' => Carbon::parse($payment->payed_at)->format('d.m.Y'), 'amount' => $payment->amount];
                }
            }
            $item['all_payments'] = (int)$client->sum_all_payment;
            $item['list_remmitances'] = $client->remittances->toArray();
            $result->push($item);
        }
        return $result;
    }

    /**
     * Функция для своей пагинации
     */
    protected function yourPaginator(array $collection, int $per_page, Request $request) : Paginator
    {
        $total = count($collection);
        $current_page = $request->page ?? 1;

        $starting_point = ($current_page * $per_page) - $per_page;

        $array = array_slice($collection, $starting_point, $per_page, true);
        $result= new Paginator($array, $total, $per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
        return $result;
    }
}
