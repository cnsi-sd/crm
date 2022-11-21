<?php

namespace App\Helpers\Builder\Table\Features;

use App\Helpers\Builder\Table\TableBuilder;
use Illuminate\Http\Request;

trait Orderable
{
    private ?string $order_by = null;
    private ?string $order_way = null;

    private function setOrderBy(Request $request)
    {
        $orderby_param = 'ob_' . $this->identifier;
        $orderway_param = 'ow_' . $this->identifier;

        if ($request->has([$orderby_param, $orderway_param])) {
            $this->order_by = $request->get($orderby_param);
            $this->order_way = $request->get($orderway_param);
        }
    }

    public function getOrderByUrl(string $order_way, string $order_by): string
    {
        $base_url = $this->request->url();
        $params = $this->request->query();

        $page_param = 'page_' . $this->identifier;
        $orderby_param = 'ob_' . $this->identifier;
        $orderway_param = 'ow_' . $this->identifier;

        $params[$orderby_param] = $order_by;
        $params[$orderway_param] = $order_way;

        if(isset($params[$page_param]))
            unset($params[$page_param]);

        array_walk($params, function (&$item, $key) {
            $item = $key . '=' . $item;
        });

        return $base_url . '?' . implode('&', array_values($params));
    }
}
