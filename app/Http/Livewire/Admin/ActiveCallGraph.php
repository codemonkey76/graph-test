<?php

namespace App\Http\Livewire\Admin;

use App\Models\Server;
use App\Services\FusionApiService;
use Carbon\Carbon;
use Livewire\Component;

class ActiveCallGraph extends Component
{
    public string $chartId;
    public bool $stacked = false;
    public string $date;

    public function __construct($chartId)
    {
        $this->chartId = $chartId;
    }

    public function render()
    {
        info("Emitting event: refreshChartData-{$this->chartId}");
        $this->emit("refreshChartData-{$this->chartId}", [
            'data' => $this->getActiveCalls()]);


        return view('livewire.admin.active-call-graph');
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    private function getActiveCalls()
    {
        $data = (object) null;
        $datasets = [];

        $reportDate = Carbon::createFromFormat("Y-m-d", $this->date);
        Server::active()->each(function ($server) use ($data, &$datasets, $reportDate) {
            $api = new FusionApiService($server);
            $api->login();

            $calls = collect($api->getActiveCalls($reportDate))
                ->map(fn($call) => (object) [
                    'calls' => $call->inbound + $call->outbound,
                    'label' => Carbon::createFromTimestamp(strtotime($call->timestamp))->format('h:i')
                ]);

            // Get the time labels for only the first server
            if (!property_exists($data, 'labels')) {
                $data->labels = $calls->pluck('label')->toArray();
            }

            $dataset = (object) [
                'label'           => $server->name,
                'backgroundColor' => $server->backgroundColor,
                'borderColor'     => $server->borderColor,
                'data' => $calls->pluck('calls')->toArray()
            ];

            $datasets[] = $dataset;
        });
        $data->datasets = $datasets;

        return $data;
    }
}
