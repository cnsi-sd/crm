<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Alert;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Ticket;
use App\Models\Channel\Order;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if ($request->input()){
            $term = $request->input('term');
            $queryTicket = Ticket::query()->where('id',$term)->exists();
            if ($queryTicket) {
                return redirect()->route('ticket', [$term]);
            } else {
                $queryOrder = Order::query()->where('channel_order_number', $term)->first();
                if($queryOrder) {
                    return redirect()->route('ticket', [$queryOrder->tickets()->first()->id]);
                }
            }
            alert::toastError(__('app.no_results'));
            return redirect()->route('all_tickets');
        } else {
            alert::toastWarning(__('app.no_term'));
            return redirect()->route('all_tickets');
        }
    }
}

?>
