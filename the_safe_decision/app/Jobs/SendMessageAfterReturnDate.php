<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use App\Models\RentalContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageAfterReturnDate implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $contract;

    public function __construct(RentalContract $contract)
    {
        $this->contract = $contract;
    }

    public function handle()
    {
        // Retrieve the latest contract details
        $contract = RentalContract::find($this->contract->id);

        if ($contract && $contract->status_id == 1) {
            $whatsappService = new WhatsAppService();
            $whatsappService->sendWhatsAppMessage(
                $contract->tenant->whatsapp_number,
                "ملاحظة: فات موعد تسليم السيارة {$contract->return_date}"
            );
        }
    }
}
