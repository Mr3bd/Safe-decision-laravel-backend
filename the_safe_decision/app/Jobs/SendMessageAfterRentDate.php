<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use App\Models\RentalContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageAfterRentDate implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $contract;

    public function __construct(RentalContract $contract)
    {
        $this->contract = $contract;
    }

    public function handle()
    {
        $whatsappService = new WhatsAppService();
        $whatsappService->sendWhatsAppMessage(
            $this->contract->tenant->whatsapp_number,
            "تذكير: سوف يبدأ عقد التاجير عند: {$this->contract->rent_date}"
        );
    }
}
