<?php

namespace app\Http\controllers;

use Illuminate\Http\Request;
use App\Repositories\AcceptChannelRepository;
use App\Repositories\PaymentChannelRepository;

class OverallController{
	
	public function overallTrades(AcceptChannelRepository $AcceptChannelRepository, PaymentChannelRepository $PaymentChannelRepository){
		
		$data['accept_channel_list'] = $AcceptChannelRepository->all(['sign', 'name']);
		$data['payment_channel'] = $PaymentChannelRepository->all(['key', 'name']);

		return view('overall.trades_index', $data);
	}
	
}

